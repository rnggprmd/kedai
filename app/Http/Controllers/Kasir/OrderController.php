<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['table', 'items'])->withCount('items');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->paginate(20);

        return view('kasir.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['table', 'items.menu', 'payment']);
        return view('kasir.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:confirmed,preparing,ready,completed,cancelled',
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
            'metode' => 'required|in:tunai,qris,transfer,debit',
            'jumlah_bayar' => 'required|numeric|min:' . $order->total_harga,
        ]);

        $jumlahKembali = $request->jumlah_bayar - $order->total_harga;

        Payment::create([
            'order_id' => $order->id,
            'metode' => $request->metode,
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
