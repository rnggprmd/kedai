<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $order->kode_order }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; line-height: 1.6; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; }
        .header { text-align: center; margin-bottom: 40px; }
        .header h1 { margin: 0; color: #4f46e5; font-size: 28px; }
        .header p { margin: 5px 0; color: #666; }
        .details { margin-bottom: 40px; display: table; width: 100%; }
        .details div { display: table-cell; width: 50%; }
        .details h4 { margin: 0 0 10px 0; font-size: 14px; text-transform: uppercase; color: #999; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 40px; }
        .table th { background: #f8fafc; border-bottom: 2px solid #e2e8f0; padding: 12px; text-align: left; font-size: 13px; }
        .table td { padding: 12px; border-bottom: 1px solid #f1f5f9; font-size: 14px; }
        .total-section { text-align: right; }
        .total-section p { margin: 5px 0; }
        .total-amount { font-size: 20px; font-weight: bold; color: #4f46e5; }
        .footer { text-align: center; margin-top: 50px; font-size: 12px; color: #999; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <div class="header">
            <h1>KEDAIPOS</h1>
            <p>Self-Service Ordering System</p>
        </div>

        <div class="details">
            <div>
                <h4>Customer Info</h4>
                <p><strong>{{ $order->nama_pelanggan ?: 'Guest' }}</strong><br>
                Table: {{ $order->table->nomor_meja }}</p>
            </div>
            <div style="text-align: right;">
                <h4>Order Details</h4>
                <p>#{{ $order->kode_order }}<br>
                Date: {{ $order->created_at->format('d M Y, H:i') }}</p>
            </div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Menu Item</th>
                    <th style="text-align: center;">Qty</th>
                    <th style="text-align: right;">Price</th>
                    <th style="text-align: right;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>
                        {{ $item->nama_menu }}
                        @if($item->catatan)
                        <br><small style="color: #666;">Note: {{ $item->catatan }}</small>
                        @endif
                    </td>
                    <td style="text-align: center;">{{ $item->jumlah }}</td>
                    <td style="text-align: right;">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                    <td style="text-align: right;">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total-section">
            <p>Order Total</p>
            <p class="total-amount">{{ $order->formatted_total }}</p>
            <p style="font-size: 12px; color: #999;">Status: {{ strtoupper($order->status) }}</p>
        </div>

        <div class="footer">
            <p>Thank you for dining with us!<br>
            Please show this invoice if needed.</p>
        </div>
    </div>
</body>
</html>
