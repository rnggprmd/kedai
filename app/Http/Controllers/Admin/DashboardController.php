<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Menu;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_menu' => Menu::active()->count(),
            'total_order_hari_ini' => Order::whereDate('created_at', today())->count(),
            'pendapatan_hari_ini' => Order::whereDate('created_at', today())
                ->where('status', 'completed')
                ->sum('total_harga'),
            'order_pending' => Order::where('status', 'pending')->count(),
            'total_kasir' => User::where('role', 'kasir')->count(),
        ];

        $recent_orders = Order::with(['table', 'kasir'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_orders'));
    }
}
