<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $order->kode_order }}</title>
    <style>
        @page { margin: 0; }
        body { 
            font-family: 'Inter', 'Helvetica', Arial, sans-serif; 
            color: #1e293b; 
            line-height: 1.5; 
            margin: 0;
            padding: 40px;
            background-color: #fff;
        }
        .invoice-container {
            max-width: 800px;
            margin: auto;
        }
        .header {
            border-bottom: 2px solid #f1f5f9;
            padding-bottom: 30px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .brand {
            color: #1E1E1E;
        }
        .brand h1 {
            margin: 0;
            font-size: 32px;
            font-weight: 900;
            letter-spacing: -0.025em;
        }
        .brand p {
            margin: 0;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #94a3b8;
        }
        .invoice-meta {
            text-align: right;
        }
        .invoice-meta h2 {
            margin: 0;
            font-size: 24px;
            font-weight: 800;
            color: #0f172a;
        }
        .invoice-meta p {
            margin: 5px 0 0;
            font-size: 14px;
            color: #64748b;
            font-weight: 600;
        }
        .info-grid {
            display: flex;
            margin-bottom: 40px;
            gap: 40px;
        }
        .info-block {
            flex: 1;
        }
        .info-block h4 {
            margin: 0 0 10px;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #94a3b8;
        }
        .info-block p {
            margin: 0;
            font-size: 15px;
            font-weight: 700;
            color: #334155;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th {
            text-align: left;
            padding: 12px 15px;
            background-color: #f8fafc;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            border-bottom: 2px solid #e2e8f0;
        }
        .items-table td {
            padding: 15px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 14px;
        }
        .item-name {
            font-weight: 700;
            color: #0f172a;
        }
        .item-note {
            font-size: 12px;
            color: #94a3b8;
            font-style: italic;
            margin-top: 4px;
        }
        .qty {
            text-align: center;
            font-weight: 600;
            color: #64748b;
        }
        .price, .subtotal {
            text-align: right;
            font-weight: 700;
            color: #334155;
        }
        .summary-wrapper {
            display: flex;
            justify-content: flex-end;
        }
        .summary-table {
            width: 250px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            font-size: 14px;
        }
        .summary-row.total {
            border-top: 2px solid #f1f5f9;
            margin-top: 10px;
            padding-top: 20px;
        }
        .summary-row.total .label {
            font-weight: 800;
            color: #0f172a;
        }
        .summary-row.total .value {
            font-size: 24px;
            font-weight: 900;
            color: #1E1E1E;
        }
        .summary-row .label {
            font-weight: 600;
            color: #64748b;
        }
        .summary-row .value {
            font-weight: 700;
            color: #0f172a;
        }
        .footer {
            margin-top: 60px;
            text-align: center;
            padding-top: 30px;
            border-top: 1px dashed #e2e8f0;
        }
        .footer p {
            margin: 0;
            font-size: 13px;
            color: #94a3b8;
            font-weight: 600;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 9999px;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-top: 10px;
        }
        .status-completed { background-color: #FFD60A; color: #1E1E1E; }
        .status-pending { background-color: #1E1E1E; color: #FFFFFF; }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="header">
            <div class="brand">
                <h1>KedaiPos</h1>
                <p>Ecosystem v1.0</p>
            </div>
            <div class="invoice-meta">
                <h2>INVOICE</h2>
                <p>#{{ $order->kode_order }}</p>
            </div>
        </div>

        <div class="info-grid">
            <div class="info-block">
                <h4>Pelanggan</h4>
                <p>{{ $order->nama_pelanggan ?: 'Pelanggan Datang Langsung' }}</p>
            </div>
            <div class="info-block">
                <h4>Meja</h4>
                <p>{{ $order->table->nama_meja }}</p>
            </div>
            <div class="info-block" style="text-align: right;">
                <h4>Tanggal</h4>
                <p>{{ $order->created_at->format('d M Y, H:i') }}</p>
            </div>
        </div>

        <table class="items-table">
            <thead>
                <tr>
                    <th>Item Menu</th>
                    <th style="text-align: center;">Jumlah</th>
                    <th style="text-align: right;">Harga</th>
                    <th style="text-align: right;">Subtotal</th>
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
                    <td class="qty">{{ $item->jumlah }}</td>
                    <td class="price">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                    <td class="subtotal">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="summary-wrapper">
            <div class="summary-table">
                <div class="summary-row">
                    <span class="label">Subtotal</span>
                    <span class="value">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
                </div>
                <div class="summary-row">
                    <span class="label">Status</span>
                    <span class="value">
                        <span class="status-badge status-{{ $order->status == 'completed' ? 'completed' : 'pending' }}">
                            {{ $order->status }}
                        </span>
                    </span>
                </div>
                <div class="summary-row total">
                    <span class="label">Total Bayar</span>
                    <span class="value">{{ $order->formatted_total }}</span>
                </div>
            </div>
        </div>

        <div class="footer">
            <p>Terima kasih telah berkunjung ke KedaiPos!</p>
            <p style="margin-top: 5px; font-size: 11px;">Struk ini adalah bukti pembayaran yang sah.</p>
        </div>
    </div>
</body>
</html>
