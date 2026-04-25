<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        // Get latest 500 orders for client-side filtering performance
        $orders = Order::with(['table', 'kasir', 'items'])
            ->withCount('items')
            ->latest()
            ->take(500)
            ->get();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['table', 'kasir', 'items.menu', 'payment']);
        return view('admin.orders.show', compact('order'));
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
}
