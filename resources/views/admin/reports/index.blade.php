@extends('layouts.admin')

@section('title', 'Analitik Penjualan')
@section('page-title', 'Wawasan Bisnis')
@section('page-subtitle', 'Analisis performa restoran dan tren pertumbuhan Anda.')

@section('content')


{{-- Filter & Actions (No Print) --}}
<div class="-mt-4 lg:-mt-6 mb-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6 no-print filter-section w-full">
    <div class="bg-white p-1.5 rounded-[1.5rem] sm:rounded-full border border-slate-200 shadow-sm flex flex-col sm:flex-row items-stretch sm:items-center gap-2 lg:flex-1 lg:max-w-3xl transition-all focus-within:ring-4 focus-within:ring-brand-accent/5 focus-within:border-brand-accent">
        <form action="{{ route('admin.reports.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-2 w-full">
            <div class="relative flex-1 group w-full">
                <i class="bi bi-calendar-event absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-brand-accent transition-colors text-sm"></i>
                <input type="date" name="from" value="{{ request('from', $startDate) }}"
                    class="w-full bg-transparent border-none focus:outline-none focus:ring-0 pl-12 pr-4 py-2.5 text-sm font-bold text-slate-900 placeholder:text-slate-300 outline-none">
            </div>
            <div class="w-px h-6 bg-slate-100 hidden sm:block"></div>
            <div class="relative flex-1 group w-full">
                <i class="bi bi-calendar-check absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-brand-accent transition-colors text-sm"></i>
                <input type="date" name="to" value="{{ request('to', $endDate) }}"
                    class="w-full bg-transparent border-none focus:outline-none focus:ring-0 pl-12 pr-4 py-2.5 text-sm font-bold text-slate-900 placeholder:text-slate-300 outline-none">
            </div>
            <button type="submit" class="bg-brand-accent text-white px-8 py-2.5 rounded-full text-[10px] font-black uppercase tracking-widest transition-all shadow-lg shadow-brand-accent/20 hover:opacity-90 shrink-0">
                Perbarui
            </button>
        </form>
    </div>
    
    <div class="flex items-center">
        <a href="{{ route('admin.reports.exportPdf', request()->all()) }}" class="bg-slate-900 text-white px-8 py-4 rounded-[2rem] font-black text-[10px] uppercase tracking-widest hover:bg-slate-800 active:scale-95 transition-all shadow-xl shadow-slate-900/20 flex items-center justify-center gap-3 shrink-0">
            <i class="bi bi-file-earmark-pdf-fill text-lg text-yellow-400"></i> <span>Export PDF</span>
        </a>
    </div>
</div>


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
<div class="grid grid-cols-1 lg:grid-cols-4 gap-8 mb-10">
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

    <!-- Payment Distribution -->
    <div class="bg-white rounded-[3rem] border border-slate-200 shadow-sm overflow-hidden flex flex-col no-print">
        <div class="p-10 border-b border-slate-50 bg-slate-50/50">
            <h3 class="text-slate-900 font-black text-xl tracking-tight mb-1">Metode Pembayaran</h3>
            <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest leading-relaxed">Preferensi transaksi.</p>
        </div>
        <div class="p-8 space-y-6">
            @foreach($paymentMethods as $pm)
            <div>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-slate-900 font-black text-xs uppercase tracking-wider">
                        @if(strtolower($pm->metode) == 'tunai' || strtolower($pm->metode) == 'cash') Tunai @else Non Tunai @endif
                    </span>
                    <span class="text-slate-400 font-bold text-[10px] uppercase">{{ $pm->jumlah }}</span>
                </div>
                <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                    @php
                        $percentage = ($summary['total_pendapatan'] > 0) ? ($pm->total / $summary['total_pendapatan'] * 100) : 0;
                        $color = match($pm->metode) {
                            'cash' => 'bg-emerald-500',
                            'midtrans' => 'bg-blue-500',
                            default => 'bg-slate-500'
                        };
                    @endphp
                    <div class="{{ $color }} h-full transition-all duration-1000" style="width: {{ $percentage }}%"></div>
                </div>
                <div class="flex justify-between items-center mt-2">
                    <span class="text-slate-400 text-[9px] font-bold uppercase">{{ number_format($percentage, 1) }}% Share</span>
                    <span class="text-brand-primary font-black text-xs">Rp {{ number_format($pm->total, 0, ',', '.') }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Top Performing Menu -->
    <div class="bg-white rounded-[3rem] border border-slate-200 shadow-sm overflow-hidden flex flex-col">
        <div class="p-10 border-b border-slate-50 bg-slate-50/50">
            <h3 class="text-slate-900 font-black text-xl tracking-tight mb-1">Peringkat Hidangan</h3>
            <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest leading-relaxed">Unit terjual.</p>
        </div>
        <div class="flex-1 overflow-y-auto p-8 space-y-5 custom-scrollbar max-h-[350px]">
            @forelse($popular_menus->take(5) as $index => $menu)
            <div class="flex items-center gap-4 group">
                <div class="w-8 h-8 bg-slate-50 text-brand-accent rounded-lg flex items-center justify-center font-black text-xs flex-shrink-0 border border-slate-100 group-hover:bg-brand-accent group-hover:text-white transition-all">
                    #{{ $index + 1 }}
                </div>
                <div class="flex-1 min-w-0">
                    <h4 class="text-slate-900 font-black text-xs truncate mb-0.5">{{ $menu->nama_menu }}</h4>
                    <div class="flex items-center justify-between text-[9px]">
                        <span class="text-slate-400 font-bold uppercase">{{ $menu->total_qty }} Qty</span>
                    </div>
                </div>
            </div>
            @empty
            <p class="text-center text-slate-400 font-bold text-xs py-10">Kosong</p>
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
