<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan Kedai Wasis</title>
    <style>
        @page {
            margin: 1.5cm;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10pt;
            color: #333;
            line-height: 1.4;
        }
        .header {
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
            margin-bottom: 25px;
            text-align: center;
        }
        .logo {
            font-size: 24pt;
            font-weight: bold;
            color: #1a1a1a;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 5px;
        }
        .subtitle {
            font-size: 10pt;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .report-title {
            font-size: 16pt;
            font-weight: bold;
            margin-bottom: 10px;
            color: #000;
        }
        .period {
            font-size: 11pt;
            color: #444;
            margin-bottom: 30px;
        }
        .section-title {
            font-size: 12pt;
            font-weight: bold;
            background: #f4f4f4;
            padding: 8px 12px;
            border-left: 4px solid #333;
            margin: 25px 0 15px 0;
            text-transform: uppercase;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th {
            background-color: #333;
            color: #fff;
            text-align: left;
            padding: 10px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9pt;
        }
        td {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .font-bold {
            font-weight: bold;
        }
        .summary-card {
            margin-bottom: 30px;
        }
        .summary-item {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }
        .summary-label {
            color: #666;
            font-size: 9pt;
        }
        .summary-value {
            font-size: 14pt;
            font-weight: bold;
            color: #000;
            margin-top: 5px;
        }
        .footer {
            margin-top: 50px;
            font-size: 8pt;
            color: #999;
            text-align: center;
            border-top: 1px solid #eee;
            padding-top: 15px;
        }
        .badge {
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 8pt;
            font-weight: bold;
        }
        .badge-success { background: #dcfce7; color: #166534; }
        .badge-primary { background: #e0e7ff; color: #3730a3; }
        .row:after {
            content: "";
            display: table;
            clear: both;
        }
        .col {
            float: left;
            width: 25%;
        }
        .col-50 {
            float: left;
            width: 50%;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">Kedai Wasis</div>
        <div class="subtitle">Sistem Manajemen Restoran Modern</div>
    </div>

    <div class="text-center">
        <div class="report-title">LAPORAN ANALITIK PENJUALAN</div>
        <div class="period">
            Periode: <strong>{{ date('d M Y', strtotime($startDate)) }}</strong> s/d <strong>{{ date('d M Y', strtotime($endDate)) }}</strong>
        </div>
    </div>

    <div class="section-title">Ringkasan Performa</div>
    <div class="row">
        <div class="col">
            <div class="summary-label">Total Pendapatan</div>
            <div class="summary-value" style="color: #1a1a1a;">Rp {{ number_format($summary['total_pendapatan'], 0, ',', '.') }}</div>
        </div>
        <div class="col">
            <div class="summary-label">Total Pesanan</div>
            <div class="summary-value">{{ $summary['total_orders'] }}</div>
        </div>
        <div class="col">
            <div class="summary-label">Item Terjual</div>
            <div class="summary-value">{{ $summary['total_items_sold'] }}</div>
        </div>
        <div class="col">
            <div class="summary-label">Dibatalkan</div>
            <div class="summary-value" style="color: #dc2626;">{{ $summary['total_cancelled'] }}</div>
        </div>
    </div>

    <div class="section-title">Peringkat Menu Terlaris</div>
    <table>
        <thead>
            <tr>
                <th width="50" class="text-center">No</th>
                <th>Nama Menu</th>
                <th width="100" class="text-center">Jumlah Terjual</th>
                <th width="150" class="text-right">Total Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($popular_menus as $index => $menu)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="font-bold">{{ $menu->nama_menu }}</td>
                <td class="text-center">{{ $menu->total_qty }} Unit</td>
                <td class="text-right">Rp {{ number_format($menu->total_revenue, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center" style="padding: 30px; color: #999;">Tidak ada data penjualan untuk periode ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="row">
        <div class="col-50" style="padding-right: 15px;">
            <div class="section-title">Metode Pembayaran</div>
            <table>
                <thead>
                    <tr>
                        <th>Metode</th>
                        <th class="text-center">Jumlah</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($paymentMethods as $pm)
                    <tr>
                        <td class="font-bold">
                            @if(strtolower($pm->metode) == 'tunai' || strtolower($pm->metode) == 'cash') Tunai @else Non Tunai @endif
                        </td>
                        <td class="text-center">{{ $pm->jumlah }}</td>
                        <td class="text-right">Rp {{ number_format($pm->total, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center" style="padding: 15px; color: #999;">-</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="col-50" style="padding-left: 15px;">
            <div class="section-title">Rincian Harian</div>
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th class="text-center">Order</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($daily->take(10) as $d)
                    <tr>
                        <td>{{ date('d M Y', strtotime($d->tanggal)) }}</td>
                        <td class="text-center">{{ $d->jumlah_order }}</td>
                        <td class="text-right">Rp {{ number_format($d->total, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center" style="padding: 15px; color: #999;">-</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="footer">
        Dicetak pada: {{ now()->format('d/m/Y H:i:s') }} oleh Admin Kedai Wasis<br>
        Laporan ini dihasilkan secara otomatis oleh sistem.
    </div>
</body>
</html>
