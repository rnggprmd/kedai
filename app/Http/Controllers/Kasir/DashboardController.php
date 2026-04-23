<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        // Fetch orders for the dashboard (today's orders and active orders)
        $orders = Order::with(['table', 'items'])
            ->whereDate('created_at', today())
            ->orWhereIn('status', ['pending', 'confirmed', 'preparing', 'ready'])
            ->latest()
            ->get();

        return view('kasir.dashboard', compact('orders'));
    }
}
