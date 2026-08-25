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
        $today = today();

        $stats = [
            'total_menu'           => Menu::active()->count(),
            'total_order_hari_ini' => Order::whereDate('created_at', $today)->count(),
            'pendapatan_hari_ini'  => Order::whereDate('created_at', $today)
                ->where('status', 'completed')
                ->sum('total_harga'),
            'order_pending'        => Order::where('status', 'pending')->count(),
            'total_kasir'          => User::where('role', 'kasir')->count(),
        ];

        $recent_orders = Order::with(['table', 'kasir'])
            ->latest()
            ->take(10)
            ->get();

        // Data for chart: Last 7 days (Aggregated in-memory from a single query)
        $start7Days = now()->subDays(6)->startOfDay();
        $completedOrders7Days = Order::where('created_at', '>=', $start7Days)
            ->where('status', 'completed')
            ->get(['created_at', 'total_harga']);

        $chart_data = ['labels' => [], 'data' => []];
        for ($i = 6; $i >= 0; $i--) {
            $day = now()->subDays($i);
            $targetDate = $day->toDateString();
            $label = $day->format('D');
            $revenue = $completedOrders7Days->filter(function ($order) use ($targetDate) {
                return $order->created_at && $order->created_at->toDateString() === $targetDate;
            })->sum('total_harga');
            
            $chart_data['labels'][] = $label;
            $chart_data['data'][]   = (int) $revenue;
        }

        return view('admin.dashboard', compact('stats', 'recent_orders', 'chart_data'));
    }
}
