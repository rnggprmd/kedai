<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $order->kode_order }}</title>
    <style>
        @page { margin: 1.5cm; }
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            color: #1e293b; 
            line-height: 1.4; 
            margin: 0;
            padding: 0;
            background-color: #fff;
            font-size: 10pt;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-table {
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        .brand-title {
            font-size: 22pt;
            font-weight: bold;
            color: #1E1E1E;
            margin: 0;
            letter-spacing: -0.5px;
        }
        .brand-subtitle {
            font-size: 8pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #94a3b8;
            margin-top: 3px;
        }
        .invoice-title {
            font-size: 18pt;
            font-weight: bold;
            color: #0f172a;
            margin: 0;
            text-align: right;
        }
        .invoice-code {
            font-size: 10pt;
            color: #64748b;
            font-weight: bold;
            text-align: right;
            margin-top: 3px;
        }
        .info-table {
            margin-bottom: 25px;
            background: #f8fafc;
            border-radius: 8px;
            padding: 12px;
        }
        .info-label {
            font-size: 7.5pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #94a3b8;
            padding-bottom: 2px;
        }
        .info-value {
            font-size: 10.5pt;
            font-weight: bold;
            color: #1e293b;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .items-table th {
            text-align: left;
            padding: 10px 12px;
            background-color: #1E1E1E;
            color: #ffffff;
            font-size: 8.5pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .items-table td {
            padding: 12px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 9.5pt;
        }
        .item-name {
            font-weight: bold;
            color: #0f172a;
        }
        .item-note {
            font-size: 8pt;
            color: #94a3b8;
            font-style: italic;
            margin-top: 2px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .summary-table {
            width: 100%;
            margin-top: 10px;
        }
        .summary-box {
            width: 260px;
            float: right;
            border-collapse: collapse;
        }
        .summary-box td {
            padding: 6px 0;
            font-size: 9.5pt;
        }
        .summary-box .total-row td {
            border-top: 2px solid #1E1E1E;
            padding-top: 10px;
            margin-top: 5px;
        }
        .total-amount {
            font-size: 16pt;
            font-weight: bold;
            color: #1E1E1E;
        }
        .status-pill {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 7.5pt;
            font-weight: bold;
            text-transform: uppercase;
            background: #dcfce7;
            color: #166534;
        }
        .footer {
            margin-top: 60px;
            text-align: center;
            padding-top: 20px;
            border-top: 1px dashed #cbd5e1;
            font-size: 8.5pt;
            color: #94a3b8;
            clear: both;
        }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td style="vertical-align: top;">
                <div class="brand-title">Kedai Wasis</div>
                <div class="brand-subtitle">Pusat Kuliner & Kopi</div>
            </td>
            <td style="vertical-align: top; text-align: right;">
                <div class="invoice-title">INVOICE</div>
                <div class="invoice-code">#{{ $order->kode_order }}</div>
            </td>
        </tr>
    </table>

    <table class="info-table">
        <tr>
            <td width="35%" style="vertical-align: top;">
                <div class="info-label">Pelanggan</div>
                <div class="info-value">{{ $order->nama_pelanggan ?: 'Walk-in Guest' }}</div>
            </td>
            <td width="30%" style="vertical-align: top;">
                <div class="info-label">Meja</div>
                <div class="info-value">{{ $order->table->nama_meja }}</div>
            </td>
            <td width="35%" style="vertical-align: top; text-align: right;">
                <div class="info-label">Waktu Pemesanan</div>
                <div class="info-value">{{ $order->created_at->format('d M Y, H:i') }}</div>
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th>Item Menu</th>
                <th width="70" class="text-center">Jumlah</th>
                <th width="120" class="text-right">Harga</th>
                <th width="120" class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>
                    <div class="item-name">{{ $item->nama_menu }}</div>
                    @if($item->catatan)
                        <div class="item-note">"{{ $item->catatan }}"</div>
                    @endif
                </td>
                <td class="text-center">{{ $item->jumlah }}</td>
                <td class="text-right">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="width: 100%;">
        <table class="summary-box">
            <tr>
                <td style="color: #64748b;">Subtotal</td>
                <td class="text-right" style="font-weight: bold;">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="color: #64748b;">Status Pembayaran</td>
                <td class="text-right">
                    <span class="status-pill">{{ $order->status == 'completed' ? 'Lunas' : strtoupper($order->status) }}</span>
                </td>
            </tr>
            <tr class="total-row">
                <td style="font-weight: bold; font-size: 11pt;">Total Bayar</td>
                <td class="text-right total-amount">{{ $order->formatted_total }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p style="margin: 0; font-weight: bold; color: #64748b;">Terima kasih telah berkunjung ke Kedai Wasis!</p>
        <p style="margin: 3px 0 0 0;">Struk/Invoice ini adalah bukti pembayaran yang sah.</p>
    </div>
</body>
</html>
