@extends('layouts.admin')

@section('title', 'Sales Analytics')
@section('page-title', 'Business Insights')
@section('page-subtitle', 'Analyze your restaurant performance and growth trends.')

@section('content')
<!-- Filter & Action Header -->
<div class="mb-10 flex flex-col lg:flex-row lg:items-center justify-between gap-8">
    <div class="bg-white p-4 lg:p-6 rounded-[2rem] border border-slate-200 shadow-sm flex-1">
        <form action="{{ route('admin.reports.index') }}" method="GET" class="flex flex-col md:flex-row items-center gap-6">
            <div class="flex-1 w-full">
                <label class="block text-slate-400 text-[10px] font-black uppercase tracking-widest mb-2 ml-1">Range Start</label>
                <input type="date" name="from" class="w-full bg-slate-50 border-none rounded-xl px-5 py-3 font-bold text-slate-900 focus:ring-2 focus:ring-indigo-600 transition-all" value="{{ request('from', now()->startOfMonth()->format('Y-m-d')) }}">
            </div>
            <div class="flex-1 w-full">
                <label class="block text-slate-400 text-[10px] font-black uppercase tracking-widest mb-2 ml-1">Range End</label>
                <input type="date" name="to" class="w-full bg-slate-50 border-none rounded-xl px-5 py-3 font-bold text-slate-900 focus:ring-2 focus:ring-indigo-600 transition-all" value="{{ request('to', now()->format('Y-m-d')) }}">
            </div>
            <button type="submit" class="w-full md:w-auto bg-slate-900 text-white font-extrabold px-10 py-3.5 rounded-xl hover:bg-indigo-600 transition-all shadow-xl shadow-slate-200 flex items-center justify-center gap-3">
                <i class="bi bi-funnel-fill"></i> Update Analysis
            </button>
        </form>
    </div>
    
    <button onclick="window.print()" class="bg-white text-slate-600 border border-slate-200 font-extrabold px-8 py-5 rounded-[2rem] hover:bg-slate-50 transition-all flex items-center gap-3 shadow-sm">
        <i class="bi bi-printer-fill"></i> Export PDF
    </button>
</div>

<!-- Key Metrics Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-10">
    <div class="bg-slate-900 p-8 rounded-[2.5rem] text-white shadow-2xl relative overflow-hidden group">
        <div class="relative z-10">
            <div class="text-indigo-400 text-[10px] font-black uppercase tracking-[0.2em] mb-2">Total Revenue</div>
            <div class="text-3xl font-black tracking-tighter mb-4">Rp {{ number_format($summary['total_pendapatan'], 0, ',', '.') }}</div>
            <div class="flex items-center gap-2 text-emerald-400 text-[10px] font-black uppercase">
                <i class="bi bi-graph-up-arrow"></i> Target Met
            </div>
        </div>
        <div class="absolute -bottom-10 -right-10 w-32 h-32 bg-indigo-600/20 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>
    </div>
    
    <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm group">
        <div class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mb-2 group-hover:text-indigo-600 transition-colors">Success Orders</div>
        <div class="text-3xl text-slate-900 font-black tracking-tighter mb-4">{{ $summary['total_orders'] }}</div>
        <div class="text-slate-400 text-[10px] font-bold uppercase tracking-widest flex items-center gap-2">
            <i class="bi bi-check2-circle text-emerald-500 text-sm"></i> Order Completion
        </div>
    </div>

    <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm">
        <div class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mb-2">Items Sold</div>
        <div class="text-3xl text-slate-900 font-black tracking-tighter mb-4">{{ $summary['total_items_sold'] }}</div>
        <div class="text-slate-400 text-[10px] font-bold uppercase tracking-widest flex items-center gap-2">
            <i class="bi bi-box-seam text-indigo-500 text-sm"></i> Product Velocity
        </div>
    </div>

    <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm group">
        <div class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mb-2 group-hover:text-rose-600 transition-colors">Cancelled</div>
        <div class="text-3xl text-rose-600 font-black tracking-tighter mb-4">{{ $summary['total_cancelled'] }}</div>
        <div class="text-slate-400 text-[10px] font-bold uppercase tracking-widest flex items-center gap-2">
            <i class="bi bi-x-octagon text-rose-500 text-sm"></i> Loss Management
        </div>
    </div>
</div>

<!-- Chart & Popularity Section -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-10 mb-10">
    <!-- Visual Trends -->
    <div class="lg:col-span-2 bg-white p-10 rounded-[3rem] border border-slate-200 shadow-sm flex flex-col">
        <div class="flex items-center justify-between mb-10">
            <div>
                <h3 class="text-slate-900 font-black text-2xl tracking-tight">Growth Analytics</h3>
                <p class="text-slate-500 font-medium text-sm">Revenue trend for the selected period.</p>
            </div>
            <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center">
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
            <h3 class="text-slate-900 font-black text-xl tracking-tight mb-1">Top Dish Ranking</h3>
            <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest leading-relaxed">Based on total units sold.</p>
        </div>
        <div class="flex-1 overflow-y-auto p-8 space-y-6 custom-scrollbar max-h-[300px]">
            @forelse($popular_menus->take(5) as $index => $menu)
            <div class="flex items-center gap-6 group">
                <div class="w-10 h-10 bg-slate-900 text-white rounded-xl flex items-center justify-center font-black text-sm flex-shrink-0 shadow-lg shadow-slate-200 group-hover:scale-110 transition-transform">
                    #{{ $index + 1 }}
                </div>
                <div class="flex-1 min-w-0">
                    <h4 class="text-slate-900 font-extrabold text-sm truncate mb-1">{{ $menu->nama_menu }}</h4>
                    <div class="flex items-center justify-between text-[10px]">
                        <span class="text-slate-400 font-bold uppercase tracking-widest">{{ $menu->total_qty }} Sold</span>
                        <span class="text-indigo-600 font-black">Rp {{ number_format($menu->total_revenue, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
            @empty
            <p class="text-center text-slate-400 font-bold text-xs py-10">No Sales Data</p>
            @endforelse
        </div>
        
        <div class="p-10 border-t border-slate-50 bg-slate-50/10">
            <h3 class="text-slate-900 font-black text-lg tracking-tight mb-6 flex items-center gap-2">
                <i class="bi bi-wallet2 text-indigo-600"></i> Payment Strategy
            </h3>
            <div class="space-y-6">
                @foreach($paymentMethods as $pm)
                @php $pmShare = $summary['total_pendapatan'] > 0 ? ($pm->total / $summary['total_pendapatan']) * 100 : 0; @endphp
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-slate-900 font-extrabold text-[10px] uppercase tracking-widest">{{ strtoupper($pm->metode) }}</span>
                        <span class="text-slate-400 font-black text-[10px]">{{ number_format($pmShare, 1) }}%</span>
                    </div>
                    <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-indigo-600 rounded-full" style="width: {{ $pmShare }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const ctxGrowth = document.getElementById('growthChart').getContext('2d');
    const gradGrowth = ctxGrowth.createLinearGradient(0, 0, 0, 400);
    gradGrowth.addColorStop(0, 'rgba(79, 70, 229, 0.2)');
    gradGrowth.addColorStop(1, 'rgba(79, 70, 229, 0)');

    new Chart(ctxGrowth, {
        type: 'line',
        data: {
            labels: {!! json_encode($daily->pluck('tanggal')->map(fn($t) => date('d M', strtotime($t)))) !!},
            datasets: [{
                label: 'Daily Revenue',
                data: {!! json_encode($daily->pluck('total')) !!},
                borderColor: '#4f46e5',
                backgroundColor: gradGrowth,
                borderWidth: 5,
                fill: true,
                tension: 0.45,
                pointRadius: 4,
                pointBackgroundColor: '#4f46e5',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointHoverRadius: 8,
                pointHoverBackgroundColor: '#4f46e5',
                pointHoverBorderColor: '#fff',
                pointHoverBorderWidth: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0f172a',
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
                y: { beginAtZero: true, grid: { color: '#f8fafc', drawBorder: false }, ticks: { color: '#94a3b8', font: { weight: '800', size: 10 }, callback: value => 'Rp ' + value/1000 + 'k' } },
                x: { grid: { display: false }, ticks: { color: '#94a3b8', font: { weight: '800', size: 10 } } }
            }
        }
    });
</script>
@endpush
@endsection
