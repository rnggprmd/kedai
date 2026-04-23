@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<!-- High-Impact Welcome Banner (Tailwind) -->
<div class="relative overflow-hidden bg-indigo-600 rounded-[2rem] p-8 lg:p-12 text-white shadow-2xl shadow-indigo-200 mb-10">
    <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-8">
        <div class="max-w-2xl">
            <h2 class="text-3xl lg:text-5xl font-extrabold tracking-tight mb-4">
                Welcome back, {{ auth()->user()->name }}! 👋
            </h2>
            <p class="text-indigo-100 text-lg font-medium opacity-90">
                Your restaurant is performing well today. Here's a quick overview of your business metrics.
            </p>
        </div>
        <div class="hidden lg:flex items-center gap-6">
            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 border border-white/10">
                <div class="text-indigo-200 text-[10px] font-bold uppercase tracking-widest mb-1">Status</div>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
                    <span class="font-extrabold text-xl">LIVE</span>
                </div>
            </div>
            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 border border-white/10">
                <div class="text-indigo-200 text-[10px] font-bold uppercase tracking-widest mb-1">Time</div>
                <div class="font-extrabold text-xl">{{ now()->format('H:i') }}</div>
            </div>
        </div>
    </div>
    <!-- Decorative Glows -->
    <div class="absolute -top-24 -right-24 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
    <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-indigo-500/50 rounded-full blur-3xl"></div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8 mb-10">
    @php
        $statsData = [
            [
                'label' => 'Total Revenue', 
                'value' => 'Rp ' . number_format($stats['pendapatan_hari_ini'], 0, ',', '.'),
                'icon' => 'bi-wallet2',
                'color' => 'indigo',
                'trend' => '+12.5%'
            ],
            [
                'label' => 'Pending Orders', 
                'value' => $stats['order_pending'],
                'icon' => 'bi-clock-history',
                'color' => 'amber',
                'trend' => 'Action Required'
            ],
            [
                'label' => 'Success Orders', 
                'value' => $stats['total_order_hari_ini'],
                'icon' => 'bi-check-circle',
                'color' => 'emerald',
                'trend' => 'Today'
            ],
        ];
    @endphp

    @foreach($statsData as $s)
    <div class="bg-white p-8 rounded-[2rem] border border-slate-200 shadow-sm hover:shadow-xl transition-all duration-300 group">
        <div class="flex items-start justify-between mb-6">
            <div class="w-14 h-14 bg-{{ $s['color'] }}-50 text-{{ $s['color'] }}-600 rounded-2xl flex items-center justify-center transition-colors group-hover:bg-{{ $s['color'] }}-600 group-hover:text-white">
                <i class="bi {{ $s['icon'] }} text-2xl"></i>
            </div>
            <span class="text-xs font-extrabold px-3 py-1 bg-slate-50 text-slate-500 rounded-full border border-slate-100">
                {{ $s['trend'] }}
            </span>
        </div>
        <div class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-1">{{ $s['label'] }}</div>
        <div class="text-slate-900 text-3xl font-extrabold tracking-tight">{{ $s['value'] }}</div>
    </div>
    @endforeach
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8 mb-10">
    <!-- Chart Section -->
    <div class="lg:col-span-2 bg-white p-8 rounded-[2rem] border border-slate-200 shadow-sm">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h3 class="text-slate-900 font-extrabold text-xl">Revenue Growth</h3>
                <p class="text-slate-500 text-sm font-medium">Daily performance tracking</p>
            </div>
            <select class="bg-slate-50 border-none text-slate-600 font-bold text-xs rounded-xl px-4 py-2 focus:ring-2 focus:ring-indigo-500">
                <option>Last 7 Days</option>
                <option>Last 30 Days</option>
            </select>
        </div>
        <div class="h-[300px]">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <!-- Quick Stats & Actions -->
    <div class="bg-white p-8 rounded-[2rem] border border-slate-200 shadow-sm flex flex-col">
        <h3 class="text-slate-900 font-extrabold text-xl mb-8">System Metrics</h3>
        
        <div class="space-y-6 flex-1">
            <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                <div class="w-12 h-12 bg-white text-indigo-600 rounded-xl flex items-center justify-center shadow-sm">
                    <i class="bi bi-cup-hot text-xl"></i>
                </div>
                <div>
                    <div class="text-slate-900 font-extrabold text-lg leading-none">{{ $stats['total_menu'] }}</div>
                    <div class="text-slate-400 text-[10px] font-bold uppercase mt-1">Menu Items</div>
                </div>
            </div>

            <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                <div class="w-12 h-12 bg-white text-emerald-600 rounded-xl flex items-center justify-center shadow-sm">
                    <i class="bi bi-people text-xl"></i>
                </div>
                <div>
                    <div class="text-slate-900 font-extrabold text-lg leading-none">{{ $stats['total_kasir'] }}</div>
                    <div class="text-slate-400 text-[10px] font-bold uppercase mt-1">Staff Cashiers</div>
                </div>
            </div>
        </div>

        <div class="mt-10">
            <a href="{{ route('admin.reports.index') }}" class="w-full bg-slate-900 text-white font-bold py-4 rounded-2xl flex items-center justify-center gap-2 hover:bg-slate-800 transition-colors shadow-lg shadow-slate-200">
                Generate Report <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>
</div>

<!-- Recent Transactions Table -->
<div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
    <div class="p-8 border-b border-slate-100 flex items-center justify-between">
        <h3 class="text-slate-900 font-extrabold text-xl">Recent Transactions</h3>
        <a href="{{ route('admin.orders.index') }}" class="text-indigo-600 font-bold text-sm hover:underline">View All</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-slate-50">
                    <th class="px-8 py-4 text-slate-400 text-[10px] font-bold uppercase tracking-widest">Order ID</th>
                    <th class="px-8 py-4 text-slate-400 text-[10px] font-bold uppercase tracking-widest">Table</th>
                    <th class="px-8 py-4 text-slate-400 text-[10px] font-bold uppercase tracking-widest">Status</th>
                    <th class="px-8 py-4 text-slate-400 text-[10px] font-bold uppercase tracking-widest text-right">Amount</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($recent_orders as $order)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-8 py-5">
                        <div class="text-slate-900 font-bold text-sm">#{{ $order->kode_order }}</div>
                        <div class="text-slate-400 text-[10px] mt-0.5">{{ $order->created_at->format('H:i') }} WIB</div>
                    </td>
                    <td class="px-8 py-5">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-100 text-slate-700">
                            {{ $order->table->nama_meja }}
                        </span>
                    </td>
                    <td class="px-8 py-5">
                        @php
                            $statusMap = [
                                'completed' => ['bg-emerald-50', 'text-emerald-600', 'COMPLETED'],
                                'cancelled' => ['bg-rose-50', 'text-rose-600', 'CANCELLED'],
                                'ready' => ['bg-sky-50', 'text-sky-600', 'READY'],
                                'default' => ['bg-amber-50', 'text-amber-600', 'PENDING']
                            ];
                            $s = $statusMap[$order->status] ?? $statusMap['default'];
                        @endphp
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-extrabold {{ $s[0] }} {{ $s[1] }}">
                            {{ $s[2] }}
                        </span>
                    </td>
                    <td class="px-8 py-5 text-right font-extrabold text-slate-900">
                        {{ $order->formatted_total }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-8 py-10 text-center text-slate-400 font-medium">No transactions recorded today.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(99, 102, 241, 0.15)');
    gradient.addColorStop(1, 'rgba(99, 102, 241, 0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                label: 'Revenue',
                data: [180000, 260000, 210000, 320000, 280000, 450000, 410000],
                borderColor: '#6366f1',
                backgroundColor: gradient,
                borderWidth: 4,
                fill: true,
                tension: 0.4,
                pointRadius: 0,
                pointHoverRadius: 6,
                pointHoverBackgroundColor: '#6366f1',
                pointHoverBorderColor: '#fff',
                pointHoverBorderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: '#f1f5f9' }, border: { display: false }, ticks: { color: '#94a3b8', font: { weight: '600', size: 11 } } },
                x: { grid: { display: false }, border: { display: false }, ticks: { color: '#94a3b8', font: { weight: '600', size: 11 } } }
            }
        }
    });
</script>
@endpush
@endsection
