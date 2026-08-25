<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $data = $this->getReportData($request);

        return view('admin.reports.index', $data);
    }

    public function exportPdf(Request $request)
    {
        $data = $this->getReportData($request);
        
        $pdf = Pdf::loadView('admin.reports.pdf', $data)
            ->setPaper('a4', 'portrait');

        return $pdf->download('Laporan-Penjualan-' . $data['startDate'] . '-to-' . $data['endDate'] . '.pdf');
    }

    private function getReportData(Request $request)
    {
        $startDate = $request->input('from', now()->startOfMonth()->toDateString());
        $endDate   = $request->input('to', now()->toDateString());

        $startDateTime = $startDate . ' 00:00:00';
        $endDateTime   = $endDate . ' 23:59:59';

        // Ringkasan
        $summary = [
            'total_orders' => Order::whereBetween('created_at', [$startDateTime, $endDateTime])
                ->where('status', 'completed')
                ->count(),
            'total_pendapatan' => Order::whereBetween('created_at', [$startDateTime, $endDateTime])
                ->where('status', 'completed')
                ->sum('total_harga'),
            'total_items_sold' => DB::table('order_items')
                ->join('orders', 'orders.id', '=', 'order_items.order_id')
                ->where('orders.status', 'completed')
                ->whereBetween('orders.created_at', [$startDateTime, $endDateTime])
                ->sum('order_items.jumlah') ?? 0,
            'total_cancelled' => Order::whereBetween('created_at', [$startDateTime, $endDateTime])
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
            ->whereBetween('created_at', [$startDateTime, $endDateTime])
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        // Menu terlaris
        $popular_menus = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.status', 'completed')
            ->whereBetween('orders.created_at', [$startDateTime, $endDateTime])
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
            ->whereBetween('created_at', [$startDateTime, $endDateTime])
            ->select('metode', DB::raw('COUNT(*) as jumlah'), DB::raw('SUM(jumlah_bayar - jumlah_kembali) as total'))
            ->groupBy('metode')
            ->get();

        return [
            'summary'        => $summary,
            'daily'          => $daily,
            'popular_menus'  => $popular_menus,
            'paymentMethods' => $paymentMethods,
            'startDate'      => $startDate,
            'endDate'        => $endDate,
        ];
    }
}
