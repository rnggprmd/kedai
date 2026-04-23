<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Table;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
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

        $order->load('items');

        return view('customer.status', compact('table', 'order'));
    }
}
