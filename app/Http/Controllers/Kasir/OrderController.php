<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Table;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;

class OrderController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function index(Request $request)
    {
        $query = Order::with(['table', 'items'])->withCount('items');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->paginate(20);

        return view('kasir.orders.index', compact('orders'));
    }

    public function create()
    {
        $categories = Category::active()->ordered()->get();
        $menus      = Menu::with('category')->available()->orderBy('nama')->get();
        $tables     = Table::where('is_active', true)->orderBy('nama_meja')->get();

        return view('kasir.orders.create', compact('categories', 'menus', 'tables'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'table_id'          => 'required|exists:tables,id',
            'nama_pelanggan'    => 'nullable|string|max:100',
            'catatan'           => 'nullable|string|max:500',
            'items'             => 'required|array|min:1',
            'items.*.menu_id'   => 'required|exists:menus,id',
            'items.*.jumlah'    => 'required|integer|min:1',
            'items.*.catatan'   => 'nullable|string|max:255',
        ]);

        $order = Order::create([
            'table_id'       => $request->table_id,
            'nama_pelanggan' => $request->nama_pelanggan ?? 'Walk-in Guest',
            'catatan'        => $request->catatan,
            'status'         => 'confirmed',  // langsung confirmed karena kasir yg input
            'kasir_id'       => auth()->id(),
            'total_harga'    => 0,
        ]);

        foreach ($request->items as $item) {
            $menu = Menu::findOrFail($item['menu_id']);
            OrderItem::create([
                'order_id'   => $order->id,
                'menu_id'    => $menu->id,
                'nama_menu'  => $menu->nama,
                'harga'      => $menu->harga,
                'jumlah'     => $item['jumlah'],
                'subtotal'   => $menu->harga * $item['jumlah'],
                'catatan'    => $item['catatan'] ?? null,
            ]);
        }

        $order->hitungTotal();

        // Generate Midtrans Snap Token
        $params = [
            'transaction_details' => [
                'order_id' => $order->kode_order,
                'gross_amount' => (int) $order->total_harga,
            ],
            'customer_details' => [
                'first_name' => $order->nama_pelanggan ?: 'Walk-in Guest',
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            $order->update(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            \Log::error('Midtrans Error Kasir: ' . $e->getMessage());
        }

        return redirect()            ->route('kasir.orders.show', $order)
            ->with('success', 'Pesanan berhasil dibuat! Silakan proses pembayaran.');
    }

    public function show(Order $order)
    {
        $order->load(['table', 'items.menu', 'payment']);
        
        // Generate token if missing and order is not finished
        if (empty($order->snap_token) && !in_array($order->status, ['completed', 'cancelled'])) {
            $params = [
                'transaction_details' => [
                    'order_id' => $order->kode_order,
                    'gross_amount' => (int) $order->total_harga,
                ],
                'customer_details' => [
                    'first_name' => $order->nama_pelanggan ?: 'Walk-in Guest',
                ],
            ];
            try {
                $snapToken = \Midtrans\Snap::getSnapToken($params);
                $order->update(['snap_token' => $snapToken]);
            } catch (\Exception $e) {
                \Log::error('Midtrans Error Kasir Show: ' . $e->getMessage());
            }
        }

        return view('kasir.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:confirmed,completed,cancelled',
        ]);

        $order->update([
            'status' => $request->status,
            'kasir_id' => auth()->id(),
        ]);

        return back()->with('success', 'Status pesanan berhasil diperbarui.');
    }

    public function pay(Request $request, Order $order)
    {
        $request->validate([
            'metode' => 'required|in:tunai,qris,transfer,debit,midtrans,non-tunai',
            'jumlah_bayar' => 'required|numeric|min:' . $order->total_harga,
        ]);

        $jumlahKembali = $request->jumlah_bayar - $order->total_harga;

        $metodeDB = in_array($request->metode, ['midtrans', 'non-tunai']) ? 'qris' : $request->metode;

        Payment::create([
            'order_id' => $order->id,
            'metode' => $metodeDB,
            'jumlah_bayar' => $request->jumlah_bayar,
            'jumlah_kembali' => $jumlahKembali,
            'status' => 'paid',
            'kasir_id' => auth()->id(),
            'paid_at' => now(),
        ]);

        $order->update([
            'status' => 'completed',
            'kasir_id' => auth()->id(),
        ]);

        return back()->with('success', 'Pembayaran berhasil diproses. Kembalian: Rp ' . number_format($jumlahKembali, 0, ',', '.'));
    }
}
