@extends('layouts.admin')

@section('title', 'Analitik Penjualan')
@section('page-title', 'Wawasan Bisnis')
@section('page-subtitle', 'Analisis performa restoran dan tren pertumbuhan Anda.')

@section('content')
<div class="mb-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
    {{-- Unified Filter Bar (Pill Style) --}}
    <div class="bg-white p-1.5 rounded-[1.5rem] sm:rounded-full border border-slate-200 shadow-sm flex flex-col sm:flex-row items-stretch sm:items-center gap-2 w-full max-w-2xl transition-all focus-within:ring-4 focus-within:ring-brand-accent/5 focus-within:border-brand-accent">
        <form action="{{ route('admin.reports.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-2 w-full">
            {{-- From Date --}}
            <div class="relative flex-1 group w-full">
                <i class="bi bi-calendar-event absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-brand-accent transition-colors text-sm"></i>
                <input type="date" name="from" value="{{ request('from', now()->startOfMonth()->format('Y-m-d')) }}"
                    class="w-full bg-transparent border-none focus:outline-none focus:ring-0 pl-12 pr-4 py-2.5 text-sm font-bold text-slate-900 placeholder:text-slate-300 outline-none">
            </div>

            {{-- Subtle Divider --}}
            <div class="w-px h-6 bg-slate-100 hidden sm:block"></div>

            {{-- To Date --}}
            <div class="relative flex-1 group w-full">
                <i class="bi bi-calendar-check absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-brand-accent transition-colors text-sm"></i>
                <input type="date" name="to" value="{{ request('to', now()->format('Y-m-d')) }}"
                    class="w-full bg-transparent border-none focus:outline-none focus:ring-0 pl-12 pr-4 py-2.5 text-sm font-bold text-slate-900 placeholder:text-slate-300 outline-none">
            </div>

            {{-- Filter Button --}}
            <button type="submit" class="bg-brand-accent text-white px-8 py-2.5 rounded-full text-[10px] font-black uppercase tracking-widest transition-all shadow-lg shadow-brand-accent/20 hover:opacity-90 shrink-0">
                Perbarui
            </button>
        </form>
    </div>
    
    <button onclick="window.print()" class="bg-brand-primary text-white px-8 py-3.5 rounded-full font-black text-[10px] uppercase tracking-widest hover:opacity-90 active:scale-95 transition-all shadow-xl shadow-brand-primary/20 flex items-center justify-center gap-3 shrink-0">
        <i class="bi bi-printer-fill text-lg"></i> Export PDF
    </button>
</div>

<!-- Key Metrics Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-10">
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
                <i class="bi bi-activity text-2xl"></i>
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
</div>

@push('scripts')
<script>
    const ctxGrowth = document.getElementById('growthChart').getContext('2d');
    const gradGrowth = ctxGrowth.createLinearGradient(0, 0, 0, 400);
    gradGrowth.addColorStop(0, 'rgba(157, 78, 221, 0.15)');
    gradGrowth.addColorStop(1, 'rgba(157, 78, 221, 0)');

    new Chart(ctxGrowth, {
        type: 'line',
        data: {
            labels: {!! json_encode($daily->pluck('tanggal')->map(fn($t) => date('d M', strtotime($t)))) !!},
            datasets: [{
                label: 'Pendapatan Harian',
                data: {!! json_encode($daily->pluck('total')) !!},
                borderColor: '#9D4EDD',
                backgroundColor: gradGrowth,
                borderWidth: 4,
                fill: true,
                tension: 0.45,
                pointRadius: 0,
                pointHoverRadius: 6,
                pointHoverBackgroundColor: '#9D4EDD',
                pointHoverBorderColor: '#fff',
                pointHoverBorderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 12,
                    titleFont: { size: 10, weight: '800' },
                    bodyFont: { size: 14, weight: '800' },
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(context.raw);
                        }
                    }
                }
            },
            scales: {
                y: { beginAtZero: true, grid: { color: '#f1f5f9', drawBorder: false }, ticks: { color: '#94a3b8', font: { weight: '800', size: 10 }, callback: value => 'Rp ' + value/1000 + 'k' } },
                x: { grid: { display: false }, ticks: { color: '#94a3b8', font: { weight: '800', size: 10 } } }
            }
        }
    });
</script>
@endpush
@endsection
