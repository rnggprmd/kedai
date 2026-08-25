<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil pesanan hari ini beserta pesanan aktif (pending & confirmed)
        $orders = Order::with(['table', 'items'])
            ->where(function ($query) {
                $query->whereDate('created_at', today())
                      ->orWhereIn('status', ['pending', 'confirmed']);
            })
            ->latest()
            ->get();

        $todayOrders = $orders->filter(function ($order) {
            return $order->created_at && $order->created_at->isToday();
        });

        // Data chart: Intensitas transaksi per jam hari ini (Database-agnostic & in-memory)
        $hourly_data = [
            'labels' => [],
            'data' => [],
        ];

        $hours = ['09:00', '11:00', '13:00', '15:00', '17:00', '19:00', '21:00'];
        foreach ($hours as $hour) {
            $h = (int) explode(':', $hour)[0];
            $count = $todayOrders->filter(function ($order) use ($h) {
                $orderHour = (int) $order->created_at->format('H');
                return $orderHour >= $h && $orderHour < ($h + 2);
            })->count();

            $hourly_data['labels'][] = $hour;
            $hourly_data['data'][] = $count;
        }

        // 10 pesanan terbaru hari ini
        $recent_orders = $todayOrders->take(10)->values();

        return view('kasir.dashboard', compact('orders', 'hourly_data', 'recent_orders'));
    }
}
