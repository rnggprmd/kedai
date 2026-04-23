@extends('layouts.kasir')

@section('title', 'Service Control')

@section('content')
<!-- Kasir Welcome Banner (Tailwind Dark Theme) -->
<div class="relative overflow-hidden bg-slate-900 rounded-[2rem] p-8 lg:p-12 text-white shadow-2xl mb-10">
    <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-8">
        <div>
            <h2 class="text-3xl lg:text-5xl font-extrabold tracking-tight mb-4">
                Good Luck, {{ auth()->user()->name }}! ☕
            </h2>
            <p class="text-slate-400 text-lg font-medium">
                Ready to serve your customers with excellence today.
            </p>
        </div>
        <div class="flex items-center gap-6">
            <div class="bg-white/5 backdrop-blur-md rounded-2xl p-5 border border-white/10">
                <div class="text-slate-500 text-[10px] font-bold uppercase tracking-widest mb-1">Queue Load</div>
                <div class="flex items-center gap-3">
                    <div class="w-3 h-3 bg-amber-400 rounded-full shadow-[0_0_10px_#f59e0b]"></div>
                    <div class="font-extrabold text-2xl">{{ $orders->whereIn('status', ['pending', 'preparing'])->count() }} <span class="text-slate-500 text-sm font-bold">Active</span></div>
                </div>
            </div>
        </div>
    </div>
    <!-- Abstract Background -->
    <div class="absolute top-0 right-0 w-1/2 h-full bg-indigo-600/10 skew-x-12 transform origin-right"></div>
</div>

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-8 mb-10">
    @php
        $kasirStats = [
            ['label' => 'Pending', 'val' => $orders->where('status', 'pending')->count(), 'color' => 'amber', 'icon' => 'bi-hourglass-split'],
            ['label' => 'Preparing', 'val' => $orders->where('status', 'preparing')->count(), 'color' => 'sky', 'icon' => 'bi-fire'],
            ['label' => 'Ready', 'val' => $orders->where('status', 'ready')->count(), 'color' => 'emerald', 'icon' => 'bi-bell-fill'],
            ['label' => 'Completed', 'val' => $orders->where('status', 'completed')->count(), 'color' => 'indigo', 'icon' => 'bi-cash-stack'],
        ];
    @endphp

    @foreach($kasirStats as $s)
    @php
        $colorClasses = [
            'amber' => 'bg-amber-50 text-amber-600',
            'sky' => 'bg-sky-50 text-sky-600',
            'emerald' => 'bg-emerald-50 text-emerald-600',
            'indigo' => 'bg-indigo-50 text-indigo-600',
        ];
        $currentClass = $colorClasses[$s['color']] ?? 'bg-slate-50 text-slate-600';
    @endphp
    <div class="bg-white p-6 lg:p-8 rounded-[2rem] border border-slate-200 shadow-sm hover:shadow-xl transition-all duration-300">
        <div class="flex items-center gap-4 mb-4">
            <div class="w-12 h-12 {{ $currentClass }} rounded-2xl flex items-center justify-center">
                <i class="bi {{ $s['icon'] }} text-xl"></i>
            </div>
            <div class="text-slate-400 text-[10px] font-bold uppercase tracking-widest">{{ $s['label'] }}</div>
        </div>
        <div class="text-slate-900 text-3xl lg:text-4xl font-extrabold tracking-tight">{{ $s['val'] }}</div>
    </div>
    @endforeach
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8 mb-10">
    <!-- Load Chart -->
    <div class="lg:col-span-2 bg-white p-8 rounded-[2rem] border border-slate-200 shadow-sm">
        <h3 class="text-slate-900 font-extrabold text-xl mb-8">Workload Intensity</h3>
        <div class="h-[300px]">
            <canvas id="loadChart"></canvas>
        </div>
    </div>

    <!-- Rapid Access Actions -->
    <div class="bg-indigo-600 p-8 rounded-[2rem] text-white shadow-xl flex flex-col">
        <h3 class="font-extrabold text-xl mb-8">Rapid Access</h3>
        
        <div class="space-y-4 flex-1">
            <a href="{{ route('kasir.orders.index') }}" class="group flex items-center gap-4 p-4 bg-white/10 hover:bg-white/20 rounded-2xl border border-white/10 transition-all">
                <div class="w-12 h-12 bg-white text-indigo-600 rounded-xl flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                    <i class="bi bi-receipt text-xl"></i>
                </div>
                <div>
                    <div class="font-extrabold text-sm leading-none">Order Queue</div>
                    <div class="text-indigo-200 text-[10px] font-bold uppercase mt-1">Manage Service</div>
                </div>
            </a>

            <a href="{{ route('kasir.menus.index') }}" class="group flex items-center gap-4 p-4 bg-white/10 hover:bg-white/20 rounded-2xl border border-white/10 transition-all">
                <div class="w-12 h-12 bg-white text-indigo-600 rounded-xl flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                    <i class="bi bi-book text-xl"></i>
                </div>
                <div>
                    <div class="font-extrabold text-sm leading-none">Menu Catalog</div>
                    <div class="text-indigo-200 text-[10px] font-bold uppercase mt-1">Check Availability</div>
                </div>
            </a>
        </div>

        <div class="mt-10 p-4 bg-white/5 rounded-2xl border border-white/5">
            <p class="text-xs text-indigo-200 font-medium leading-relaxed">
                <span class="text-white font-bold">Pro Tip:</span> Prioritize pending orders to maintain a smooth kitchen flow.
            </p>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const ctxLoad = document.getElementById('loadChart').getContext('2d');
    new Chart(ctxLoad, {
        type: 'bar',
        data: {
            labels: ['10am', '12pm', '2pm', '4pm', '6pm', '8pm', '10pm'],
            datasets: [{
                label: 'Orders',
                data: [10, 25, 15, 20, 35, 28, 12],
                backgroundColor: '#6366f1',
                borderRadius: 12,
                barThickness: 24
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: '#f1f5f9' }, border: { display: false }, ticks: { color: '#94a3b8', font: { weight: '600', size: 10 } } },
                x: { grid: { display: false }, border: { display: false }, ticks: { color: '#94a3b8', font: { weight: '600', size: 10 } } }
            }
        }
    });
</script>
@endpush
@endsection
