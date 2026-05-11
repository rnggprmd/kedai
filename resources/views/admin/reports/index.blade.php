@extends('layouts.admin')

@section('title', 'Analitik Penjualan')
@section('page-title', 'Wawasan Bisnis')
@section('page-subtitle', 'Analisis performa restoran dan tren pertumbuhan Anda.')

@section('content')
<style>
    @media print {
        @page { size: A4 portrait; margin: 15mm; }
        aside, header, footer, .no-print, button, .topbar-actions, .filter-section { display: none !important; }
        .lg\:ml-\[280px\], #sidebar { margin-left: 0 !important; transform: none !important; position: relative !important; display: none !important; }
        main { margin: 0 !important; padding: 0 !important; width: 100% !important; }
        .lg\:p-10 { padding: 0 !important; }
        body { background: white !important; width: 100% !important; color: #0f172a !important; }
        .bg-slate-50 { background: white !important; }
        .bg-white { border: none !important; box-shadow: none !important; }
        .rounded-\[2\.5rem\], .rounded-\[3rem\], .rounded-2xl { border-radius: 0 !important; }
        
        /* Layout adjustments for print */
        .grid { display: block !important; }
        .grid-cols-4, .grid-cols-3 { grid-template-columns: 1fr !important; }
        .mb-10 { margin-bottom: 2rem !important; }
        
        /* Force tables to look like invoices */
        .print-table { display: table !important; width: 100% !important; border-collapse: collapse !important; margin-bottom: 2rem !important; }
        .print-table th { background: #f8fafc !important; border-bottom: 2px solid #e2e8f0 !important; padding: 12px 16px !important; text-align: left !important; font-size: 10px !important; text-transform: uppercase !important; font-weight: 900 !important; color: #64748b !important; }
        .print-table td { border-bottom: 1px solid #f1f5f9 !important; padding: 12px 16px !important; font-size: 12px !important; color: #1e293b !important; }
        .print-table .total-row { background: #1e1e1e !important; color: white !important; font-weight: 900 !important; }
        
        .print-header { display: flex !important; }
        .print-section-title { display: block !important; margin-bottom: 1rem !important; font-size: 14px !important; font-weight: 900 !important; text-transform: uppercase !important; color: #1e293b !important; border-left: 4px solid #9D4EDD !important; padding-left: 12px !important; }
        
        canvas { max-width: 100% !important; height: 250px !important; margin-bottom: 2rem !important; }
        .no-print-card { display: none !important; }
    }
    .print-header, .print-section-title, .print-table { display: none; }
</style>

{{-- Formal Report Header (Invoice Style) --}}
<div class="print-header mb-10 items-center justify-between border-b-2 border-slate-900 pb-8">
    <div class="flex items-center gap-6">
        <div class="w-16 h-16 bg-white border border-slate-200 rounded-xl flex items-center justify-center p-2 shadow-sm shrink-0">
            <img src="{{ asset('images/kedai wasis.png') }}" class="w-full h-full object-contain">
        </div>
        <div>
            <h1 class="text-xl font-black text-slate-900 tracking-tighter uppercase">Kedai Wasis</h1>
            <p class="text-slate-500 text-[9px] font-bold uppercase tracking-widest mt-1">Business Intelligence Report</p>
        </div>
    </div>
    <div class="text-right">
        <div class="text-[9px] text-slate-400 font-black uppercase tracking-[0.2em] mb-1">Periode Laporan</div>
        <div class="text-base font-black text-slate-900 tracking-tight">
            {{ date('d M Y', strtotime($startDate)) }} — {{ date('d M Y', strtotime($endDate)) }}
        </div>
    </div>
</div>

{{-- Filter & Actions (No Print) --}}
<div class="-mt-4 lg:-mt-6 mb-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6 no-print filter-section">
    <div class="bg-white p-1.5 rounded-[1.5rem] sm:rounded-full border border-slate-200 shadow-sm flex flex-col sm:flex-row items-stretch sm:items-center gap-2 w-full transition-all focus-within:ring-4 focus-within:ring-brand-accent/5 focus-within:border-brand-accent">
        <form action="{{ route('admin.reports.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-2 w-full">
            <div class="relative flex-1 group w-full">
                <i class="bi bi-calendar-event absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-brand-accent transition-colors text-sm"></i>
                <input type="date" name="from" value="{{ request('from', now()->startOfMonth()->format('Y-m-d')) }}"
                    class="w-full bg-transparent border-none focus:outline-none focus:ring-0 pl-12 pr-4 py-2.5 text-sm font-bold text-slate-900 placeholder:text-slate-300 outline-none">
            </div>
            <div class="w-px h-6 bg-slate-100 hidden sm:block"></div>
            <div class="relative flex-1 group w-full">
                <i class="bi bi-calendar-check absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-brand-accent transition-colors text-sm"></i>
                <input type="date" name="to" value="{{ request('to', now()->format('Y-m-d')) }}"
                    class="w-full bg-transparent border-none focus:outline-none focus:ring-0 pl-12 pr-4 py-2.5 text-sm font-bold text-slate-900 placeholder:text-slate-300 outline-none">
            </div>
            <button type="submit" class="bg-brand-accent text-white px-8 py-2.5 rounded-full text-[10px] font-black uppercase tracking-widest transition-all shadow-lg shadow-brand-accent/20 hover:opacity-90 shrink-0">
                Perbarui
            </button>
        </form>
    </div>
    
    <button onclick="window.print()" class="bg-brand-primary text-brand-secondary px-8 py-3.5 rounded-full font-black text-[10px] uppercase tracking-widest hover:opacity-90 active:scale-95 transition-all shadow-xl shadow-brand-primary/20 flex items-center justify-center gap-3 shrink-0">
        <i class="bi bi-printer-fill text-lg"></i> <span>Export PDF</span>
    </button>
</div>

{{-- 1. Summary Metrics Table (Print Only) --}}
<div class="print-section-title">Ringkasan Performa</div>
<table class="print-table">
    <thead>
        <tr>
            <th>Parameter Metrik</th>
            <th>Deskripsi Analitik</th>
            <th class="text-right">Total Akumulasi</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="font-black">Total Pendapatan</td>
            <td>Nilai kotor dari seluruh transaksi berhasil</td>
            <td class="text-right font-black text-brand-primary">Rp {{ number_format($summary['total_pendapatan'], 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="font-black">Volume Pesanan</td>
            <td>Jumlah total meja yang dilayani (Completed)</td>
            <td class="text-right font-black text-slate-900">{{ $summary['total_orders'] }} Transaksi</td>
        </tr>
        <tr>
            <td class="font-black">Produk Terjual</td>
            <td>Akumulasi unit item menu yang keluar dapur</td>
            <td class="text-right font-black text-slate-900">{{ $summary['total_items_sold'] }} Unit</td>
        </tr>
        <tr>
            <td class="font-black">Tingkat Pembatalan</td>
            <td>Pesanan yang tidak diproses (Manajemen Kerugian)</td>
            <td class="text-right font-black text-red-500">{{ $summary['total_cancelled'] }} Item</td>
        </tr>
    </tbody>
</table>

{{-- 2. Popular Menus Table (Print Only) --}}
<div class="print-section-title">Peringkat Menu Terlaris</div>
<table class="print-table">
    <thead>
        <tr>
            <th class="w-16">Rank</th>
            <th>Nama Hidangan</th>
            <th class="text-center">Unit Terjual</th>
            <th class="text-right">Total Pendapatan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($popular_menus as $index => $menu)
        <tr>
            <td class="text-center font-black">#{{ $index + 1 }}</td>
            <td class="font-black">{{ $menu->nama_menu }}</td>
            <td class="text-center font-bold">{{ $menu->total_qty }} Qty</td>
            <td class="text-right font-black text-brand-primary">Rp {{ number_format($menu->total_revenue, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

{{-- Key Metrics Grid (Visible on Screen, Hidden on Print) --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-10 no-print">
    <div class="p-8 rounded-[2.5rem] bg-brand-primary text-white shadow-2xl shadow-brand-primary/20 relative overflow-hidden group">
        <div class="relative z-10">
            <div class="text-white text-[10px] font-black uppercase tracking-[0.2em] mb-2">Total Pendapatan</div>
            <div class="text-3xl font-black tracking-tighter mb-4">Rp {{ number_format($summary['total_pendapatan'], 0, ',', '.') }}</div>
            <div class="flex items-center gap-2 text-white text-[10px] font-black uppercase">
                <i class="bi bi-graph-up-arrow"></i> Pendapatan Periode
            </div>
        </div>
        <div class="absolute -bottom-10 -right-10 w-32 h-32 bg-white/10 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>
    </div>
    
    <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm group relative overflow-hidden">
        <div class="relative z-10">
            <div class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mb-2 group-hover:text-brand-primary transition-colors">Pesanan Berhasil</div>
            <div class="text-3xl text-slate-900 font-black tracking-tighter mb-4">{{ $summary['total_orders'] }}</div>
            <div class="text-slate-400 text-[10px] font-bold uppercase tracking-widest flex items-center gap-2">
                <i class="bi bi-check2-circle text-brand-secondary text-sm"></i> Penyelesaian Pesanan
            </div>
        </div>
        <div class="absolute -right-4 -bottom-4 w-20 h-20 bg-brand-secondary/5 rounded-full opacity-0 group-hover:opacity-100 transition-all"></div>
    </div>

    <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm group relative overflow-hidden">
        <div class="relative z-10">
            <div class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mb-2 group-hover:text-brand-accent transition-colors">Item Terjual</div>
            <div class="text-3xl text-slate-900 font-black tracking-tighter mb-4">{{ $summary['total_items_sold'] }}</div>
            <div class="text-slate-400 text-[10px] font-bold uppercase tracking-widest flex items-center gap-2">
                <i class="bi bi-box-seam text-brand-accent text-sm"></i> Kecepatan Produk
            </div>
        </div>
        <div class="absolute -right-4 -bottom-4 w-20 h-20 bg-brand-accent/5 rounded-full opacity-0 group-hover:opacity-100 transition-all"></div>
    </div>

    <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm group relative overflow-hidden">
        <div class="relative z-10">
            <div class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mb-2 group-hover:text-red-500 transition-colors">Dibatalkan</div>
            <div class="text-3xl text-red-500 font-black tracking-tighter mb-4">{{ $summary['total_cancelled'] }}</div>
            <div class="text-slate-400 text-[10px] font-bold uppercase tracking-widest flex items-center gap-2">
                <i class="bi bi-x-octagon text-red-400 text-sm"></i> Manajemen Kerugian
            </div>
        </div>
        <div class="absolute -right-4 -bottom-4 w-20 h-20 bg-red-50 rounded-full opacity-0 group-hover:opacity-100 transition-all"></div>
    </div>
</div>

<!-- Chart & Popularity Section -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-10 mb-10">
    <!-- Visual Trends -->
    <div class="lg:col-span-2 bg-white p-10 rounded-[3rem] border border-slate-200 shadow-sm flex flex-col relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-brand-primary via-brand-accent to-brand-secondary opacity-20"></div>
        <div class="flex items-center justify-between mb-10">
            <div>
                <h3 class="text-slate-900 font-black text-2xl tracking-tight">Analitik Pertumbuhan</h3>
                <p class="text-slate-500 font-medium text-sm">Tren pendapatan untuk periode yang dipilih.</p>
            </div>
            <div class="w-12 h-12 bg-slate-50 text-brand-primary rounded-2xl flex items-center justify-center border border-slate-100 shadow-sm">
                <i class="bi bi-bar-chart-fill text-2xl"></i>
            </div>
        </div>
        <div class="h-[350px] flex-1">
            <canvas id="growthChart"></canvas>
        </div>
    </div>

    <!-- Top Performing Menu & Payment Distribution -->
    <div class="bg-white rounded-[3rem] border border-slate-200 shadow-sm overflow-hidden flex flex-col">
        <div class="p-10 border-b border-slate-50 bg-slate-50/50">
            <h3 class="text-slate-900 font-black text-xl tracking-tight mb-1">Peringkat Hidangan Teratas</h3>
            <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest leading-relaxed">Berdasarkan total unit terjual.</p>
        </div>
        <div class="flex-1 overflow-y-auto p-8 space-y-6 custom-scrollbar max-h-[300px]">
            @forelse($popular_menus->take(5) as $index => $menu)
            <div class="flex items-center gap-6 group">
                <div class="w-10 h-10 bg-slate-50 text-brand-accent rounded-xl flex items-center justify-center font-black text-sm flex-shrink-0 border border-slate-100 shadow-sm group-hover:bg-brand-accent group-hover:text-white transition-all duration-300">
                    #{{ $index + 1 }}
                </div>
                <div class="flex-1 min-w-0">
                    <h4 class="text-slate-900 font-black text-sm truncate mb-1">{{ $menu->nama_menu }}</h4>
                    <div class="flex items-center justify-between text-[10px]">
                        <span class="text-slate-400 font-bold uppercase tracking-widest">{{ $menu->total_qty }} Terjual</span>
                        <span class="text-brand-primary font-black">Rp {{ number_format($menu->total_revenue, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
            @empty
            <p class="text-center text-slate-400 font-bold text-xs py-10">Tidak Ada Data Penjualan</p>
            @endforelse
        </div>
        
    </div>
</div>

@push('scripts')
<script>
    const ctxGrowth = document.getElementById('growthChart').getContext('2d');

    new Chart(ctxGrowth, {
        data: {
            labels: {!! json_encode($daily->pluck('tanggal')->map(fn($t) => date('d M', strtotime($t)))) !!},
            datasets: [
                {
                    type: 'bar',
                    label: 'Pendapatan',
                    data: {!! json_encode($daily->pluck('total')) !!},
                    backgroundColor: '#9D4EDD',
                    borderRadius: 8,
                    hoverBackgroundColor: '#3C096C',
                    yAxisID: 'y',
                    order: 2
                },
                {
                    type: 'line',
                    label: 'Jumlah Order',
                    data: {!! json_encode($daily->pluck('jumlah_order')) !!},
                    borderColor: '#FFD60A',
                    borderWidth: 3,
                    backgroundColor: 'rgba(255, 214, 10, 0.1)',
                    fill: false,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#FFD60A',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    yAxisID: 'y1',
                    order: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: { 
                legend: { 
                    display: true,
                    position: 'top',
                    align: 'end',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: { size: 10, weight: '800' },
                        color: '#94a3b8'
                    }
                },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 12,
                    titleFont: { size: 10, weight: '800' },
                    bodyFont: { size: 13, weight: '800' },
                    usePointStyle: true,
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) label += ': ';
                            if (context.dataset.type === 'bar') {
                                label += 'Rp ' + new Intl.NumberFormat('id-ID').format(context.raw);
                            } else {
                                label += context.raw + ' Pesanan';
                            }
                            return label;
                        }
                    }
                }
            },
            scales: {
                y: { 
                    type: 'linear',
                    display: true,
                    position: 'left',
                    beginAtZero: true, 
                    grid: { color: '#f1f5f9', drawBorder: false }, 
                    ticks: { 
                        color: '#94a3b8', 
                        font: { weight: '800', size: 10 }, 
                        callback: value => 'Rp ' + value/1000 + 'k' 
                    } 
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    beginAtZero: true,
                    grid: { drawOnChartArea: false },
                    ticks: {
                        color: '#FFD60A',
                        font: { weight: '800', size: 10 },
                        callback: value => value + ' qty'
                    }
                },
                x: { 
                    grid: { display: false }, 
                    ticks: { color: '#94a3b8', font: { weight: '800', size: 10 } } 
                }
            }
        }
    });
</script>
@endpush
@endsection
