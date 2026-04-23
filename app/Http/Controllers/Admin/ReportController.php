<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('from', now()->startOfMonth()->toDateString());
        $endDate = $request->input('to', now()->toDateString());

        // Ringkasan
        $summary = [
            'total_orders' => Order::whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
                ->where('status', 'completed')
                ->count(),
            'total_pendapatan' => Order::whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
                ->where('status', 'completed')
                ->sum('total_harga'),
            'total_items_sold' => DB::table('order_items')
                ->join('orders', 'orders.id', '=', 'order_items.order_id')
                ->where('orders.status', 'completed')
                ->whereBetween('orders.created_at', [$startDate, $endDate . ' 23:59:59'])
                ->sum('order_items.jumlah') ?? 0,
            'total_cancelled' => Order::whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
                ->where('status', 'cancelled')
                ->count(),
        ];

        // Pendapatan per hari
        $daily = Order::select(
                DB::raw('DATE(created_at) as tanggal'),
                DB::raw('COUNT(*) as jumlah_order'),
                DB::raw('SUM(total_harga) as total')
            )
            ->where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        // Menu terlaris
        $popular_menus = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.status', 'completed')
            ->whereBetween('orders.created_at', [$startDate, $endDate . ' 23:59:59'])
            ->select(
                'order_items.nama_menu', 
                DB::raw('SUM(order_items.jumlah) as total_qty'),
                DB::raw('SUM(order_items.subtotal) as total_revenue')
            )
            ->groupBy('order_items.nama_menu')
            ->orderByDesc('total_qty')
            ->limit(10)
            ->get();

        // Metode pembayaran
        $paymentMethods = Payment::where('status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
            ->select('metode', DB::raw('COUNT(*) as jumlah'), DB::raw('SUM(jumlah_bayar) as total'))
            ->groupBy('metode')
            ->get();

        return view('admin.reports.index', compact(
            'summary', 'daily', 'popular_menus', 'paymentMethods',
            'startDate', 'endDate'
        ));
    }
}
