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
            ->orWhereIn('status', ['pending', 'confirmed'])
            ->latest()
            ->get();

        // Data for chart: Hourly intensity for today
        $hourly_data = [];
        $hours = ['09:00', '11:00', '13:00', '15:00', '17:00', '19:00', '21:00'];
        foreach ($hours as $hour) {
            $h = explode(':', $hour)[0];
            $count = Order::whereDate('created_at', today())
                ->whereRaw("HOUR(created_at) >= ?", [$h])
                ->whereRaw("HOUR(created_at) < ?", [$h + 2])
                ->count();
            
            $hourly_data['labels'][] = $hour;
            $hourly_data['data'][] = $count;
        }

        // Recent orders for table
        $recent_orders = Order::with('table')
            ->whereDate('created_at', today())
            ->latest()
            ->take(10)
            ->get();

        return view('kasir.dashboard', compact('orders', 'hourly_data', 'recent_orders'));
    }
}
