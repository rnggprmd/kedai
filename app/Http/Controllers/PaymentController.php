<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Notification;

class PaymentController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function notification(Request $request)
    {
        try {
            $notif = new Notification();
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 400);
        }

        $transaction = $notif->transaction_status;
        $type = $notif->payment_type;
        $orderId = $notif->order_id;
        $fraud = $notif->fraud_status;

        $order = Order::where('kode_order', $orderId)->first();

        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found',
            ], 404);
        }

        if ($transaction == 'capture') {
            if ($type == 'credit_card') {
                if ($fraud == 'challenge') {
                    $order->update(['status' => 'pending']);
                } else {
                    $this->markAsPaid($order, $type, $transaction);
                }
            }
        } else if ($transaction == 'settlement') {
            $this->markAsPaid($order, $type, $transaction);
        } else if ($transaction == 'pending') {
            $order->update(['status' => 'pending']);
        } else if ($transaction == 'deny' || $transaction == 'expire' || $transaction == 'cancel') {
            $order->update(['status' => 'cancelled']);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Notification processed',
        ]);
    }

    private function markAsPaid(Order $order, $paymentType, $transactionId)
    {
        $order->update(['status' => 'completed']);
        
        // Buat record payment jika belum ada (mencegah duplikasi webhook)
        if (!\App\Models\Payment::where('order_id', $order->id)->exists()) {
            \App\Models\Payment::create([
                'order_id' => $order->id,
                'metode' => 'qris', // Umumkan midtrans sebagai qris/non-tunai di DB
                'jumlah_bayar' => $order->total_harga,
                'jumlah_kembali' => 0,
                'status' => 'paid',
                'midtrans_transaction_id' => $transactionId,
            ]);
        }
    }
}
