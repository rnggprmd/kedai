<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Table;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    /**
     * Tampilkan halaman menu untuk pelanggan berdasarkan QR token meja.
     */
    public function index(string $qr_token)
    {
        $table = Table::where('qr_token', $qr_token)
            ->where('is_active', true)
            ->firstOrFail();

        $categories = Category::active()
            ->ordered()
            ->with(['menus' => function ($q) {
                $q->available();
            }])
            ->get();

        return view('customer.menu', compact('table', 'categories'));
    }

    /**
     * Proses pesanan dari pelanggan.
     */
    public function store(Request $request, string $qr_token)
    {
        $table = Table::where('qr_token', $qr_token)
            ->where('is_active', true)
            ->firstOrFail();

        $request->validate([
            'nama_pelanggan' => 'nullable|string|max:100',
            'catatan' => 'nullable|string|max:500',
            'items' => 'required|array|min:1',
            'items.*.menu_id' => 'required|exists:menus,id',
            'items.*.jumlah' => 'required|integer|min:1|max:50',
            'items.*.catatan' => 'nullable|string|max:255',
        ]);

        // Cek ketersediaan stok/status menu sebelum diproses
        foreach ($request->items as $item) {
            $menu = Menu::find($item['menu_id']);
            if (!$menu || !$menu->is_available) {
                return back()->with('error', 'Mohon maaf, menu ' . ($menu->nama ?? 'pilihan Anda') . ' saat ini sedang habis. Silakan perbarui pesanan Anda.')->withInput();
            }
        }

        return \Illuminate\Support\Facades\DB::transaction(function () use ($request, $table, $qr_token) {
            // Buat order
            $order = Order::create([
                'table_id' => $table->id,
                'nama_pelanggan' => $request->nama_pelanggan,
                'catatan' => $request->catatan,
            ]);

            // Buat order items
            foreach ($request->items as $item) {
                $menu = Menu::findOrFail($item['menu_id']);

                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $menu->id,
                    'nama_menu' => $menu->nama,
                    'harga' => $menu->harga,
                    'jumlah' => $item['jumlah'],
                    'catatan' => $item['catatan'] ?? null,
                ]);
            }

            // Hitung total
            $order->hitungTotal();

            // Midtrans Params
            $params = [
                'transaction_details' => [
                    'order_id' => $order->kode_order,
                    'gross_amount' => (int) $order->total_harga,
                ],
                'customer_details' => [
                    'first_name' => $order->nama_pelanggan ?: 'Customer',
                ],
            ];

            try {
                $snapToken = Snap::getSnapToken($params);
                $order->update(['snap_token' => $snapToken]);
            } catch (\Exception $e) {
                \Log::error('Midtrans Error: ' . $e->getMessage());
            }

            return redirect()->route('customer.order.status', [
                'qr_token' => $qr_token,
                'order' => $order->id,
            ])->with('success', 'Pesanan berhasil dikirim!');
        });
    }

    /**
     * Tampilkan status pesanan untuk pelanggan.
     */
    public function status(string $qr_token, Order $order)
    {
        $table = Table::where('qr_token', $qr_token)->firstOrFail();

        // Pastikan order milik meja ini
        abort_unless($order->table_id === $table->id, 404);

        // Jika status masih belum lunas, coba cek status ke Midtrans (Direct Check)
        // Ini sangat berguna jika Webhook terhambat atau testing di localhost
        if (!in_array($order->status, ['completed', 'cancelled'])) {
            try {
                $status = \Midtrans\Transaction::status($order->kode_order);
                
                if ($status->transaction_status == 'settlement' || $status->transaction_status == 'capture') {
                    $order->update(['status' => 'completed']);
                    
                    // Buat record payment jika belum ada
                    if (!\App\Models\Payment::where('order_id', $order->id)->exists()) {
                        \App\Models\Payment::create([
                            'order_id' => $order->id,
                            'metode' => 'qris',
                            'jumlah_bayar' => $order->total_harga,
                            'jumlah_kembali' => 0,
                            'status' => 'paid',
                            'midtrans_transaction_id' => $status->transaction_id,
                        ]);
                    }
                }
            } catch (\Exception $e) {
                // Abaikan jika transaksi belum dibuat di Midtrans atau error koneksi
            }
        }

        $order->load('items');

        return view('customer.status', compact('table', 'order'));
    }

    /**
     * Download Invoice PDF.
     */
    public function downloadInvoice(string $qr_token, Order $order)
    {
        $table = Table::where('qr_token', $qr_token)->firstOrFail();
        abort_unless($order->table_id === $table->id, 404);

        $order->load(['items', 'table']);
        
        $pdf = Pdf::loadView('customer.invoice', compact('order'));
        return $pdf->download('Invoice-' . $order->kode_order . '.pdf');
    }
}
