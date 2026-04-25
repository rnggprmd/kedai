@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<!-- High-Impact Welcome Banner (Premium Purple Theme) -->
<div class="relative overflow-hidden rounded-[2.5rem] p-10 lg:p-14 text-white shadow-2xl mb-12" style="background: linear-gradient(135deg, #240046 0%, #3C096C 100%);">
    <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-10">
        <div>
            <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-brand-primary/20 border border-brand-primary/30 rounded-full text-[10px] font-black uppercase tracking-widest mb-6">
                <span class="w-1.5 h-1.5 bg-brand-secondary rounded-full animate-ping"></span> Operasi Global Aktif
            </div>
            <h2 class="text-4xl lg:text-6xl font-black tracking-tighter mb-4 leading-none">
                Selamat Datang, {{ explode(' ', auth()->user()->name)[0] }}! <span class="text-brand-secondary">.</span>
            </h2>
            <p class="text-slate-400 text-lg font-medium max-w-md opacity-90">
                Wawasan data telah siap. Kecerdasan bisnis Anda ada dalam genggaman.
            </p>
        </div>
        <div class="flex items-center gap-6">
            <div class="bg-white/5 backdrop-blur-xl rounded-[2rem] p-8 border border-white/10 shadow-2xl">
                <div class="text-slate-500 text-[10px] font-black uppercase tracking-widest mb-3">Waktu Sistem Langsung</div>
                <div class="flex items-center gap-4">
                    <div class="w-4 h-4 bg-brand-secondary rounded-full shadow-[0_0_20px_#FFD60A]"></div>
                    <div class="font-black text-4xl tracking-tighter">{{ now()->format('H:i') }} <span class="text-slate-500 text-sm font-bold uppercase tracking-widest ml-1">WIB</span></div>
                </div>
            </div>
        </div>
    </div>
    <!-- Abstract Premium Shapes -->
    <div class="absolute top-0 right-0 w-1/3 h-full bg-brand-primary/5 blur-[120px] rounded-full translate-x-1/2"></div>
    <div class="absolute bottom-0 left-0 w-1/4 h-full bg-brand-secondary/5 blur-[100px] rounded-full -translate-x-1/2"></div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
    @php
        $statsData = [
            ['label' => 'Total Pendapatan', 'val' => 'Rp ' . number_format($stats['pendapatan_hari_ini'], 0, ',', '.'), 'color' => 'bg-brand-accent/10 text-brand-accent', 'icon' => 'bi-wallet2', 'trend' => '+12.5%'],
            ['label' => 'Antrean Pending', 'val' => $stats['order_pending'], 'color' => 'bg-amber-500/10 text-amber-500', 'icon' => 'bi-clock-history', 'trend' => 'Prioritas'],
            ['label' => 'Pesanan Berhasil', 'val' => $stats['total_order_hari_ini'], 'color' => 'bg-brand-secondary/10 text-brand-primary', 'icon' => 'bi-check2-circle', 'trend' => 'Harian'],
            ['label' => 'Total Menu', 'val' => $stats['total_menu'], 'color' => 'bg-brand-primary/10 text-brand-primary', 'icon' => 'bi-cup-hot', 'trend' => 'Aktif'],
        ];
    @endphp

    @foreach($statsData as $s)
    <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm hover:shadow-xl transition-all duration-500 group">
        <div class="flex items-center justify-between mb-6">
            <div class="w-14 h-14 {{ $s['color'] }} rounded-2xl flex items-center justify-center text-2xl shadow-sm group-hover:scale-110 transition-transform">
                <i class="bi {{ $s['icon'] }}"></i>
            </div>
            <div class="text-slate-300 font-black text-[10px] uppercase tracking-widest">{{ $s['trend'] }}</div>
        </div>
        <div class="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-1">{{ $s['label'] }}</div>
        <div class="text-slate-900 text-3xl font-black tracking-tighter">{{ $s['val'] }}</div>
    </div>
    @endforeach
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
    <!-- Visual Performance Bento -->
    <div class="lg:col-span-2 bg-white p-10 rounded-[3rem] border border-slate-200 shadow-sm">
        <div class="flex items-center justify-between mb-10">
            <div>
                <h3 class="text-slate-900 font-black text-2xl tracking-tight">Analitik Pendapatan</h3>
                <p class="text-slate-400 text-sm font-medium">Memantau tren pertumbuhan bisnis.</p>
            </div>
            <div class="flex gap-2">
                <span class="w-3 h-3 bg-brand-primary rounded-full"></span>
                <span class="w-3 h-3 bg-slate-100 rounded-full"></span>
            </div>
        </div>
        <div class="h-[320px]">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <!-- Staff & Actions Bento -->
    <div class="bg-white p-10 rounded-[3rem] border border-slate-200 shadow-sm flex flex-col">
        <h3 class="text-slate-900 font-black text-2xl tracking-tight mb-10">Kontrol Cepat</h3>
        
        <div class="space-y-4 flex-1">
            <div class="flex items-center gap-4 p-5 bg-slate-50 rounded-[1.5rem] border border-slate-100">
                <div class="w-14 h-14 bg-white text-brand-primary rounded-2xl flex items-center justify-center shadow-sm">
                    <i class="bi bi-people-fill text-2xl"></i>
                </div>
                <div>
                    <div class="text-slate-900 font-black text-xl leading-none">{{ $stats['total_kasir'] }}</div>
                    <div class="text-slate-400 text-[10px] font-black uppercase tracking-widest mt-1">Staff Kasir</div>
                </div>
            </div>

            <a href="{{ route('admin.reports.index') }}" class="group flex items-center gap-4 p-5 bg-brand-primary text-brand-secondary rounded-[1.5rem] border border-brand-primary/10 shadow-xl shadow-brand-primary/20 hover:scale-[1.02] transition-all">
                <div class="w-12 h-12 bg-white/20 text-white rounded-xl flex items-center justify-center">
                    <i class="bi bi-file-earmark-bar-graph text-xl"></i>
                </div>
                <div>
                    <div class="font-black text-sm uppercase tracking-wider">Pusat Laporan</div>
                    <div class="text-white/60 text-[10px] font-bold mt-0.5">Analitik Data Lengkap</div>
                </div>
            </a>
        </div>

        <div class="mt-12 p-6 bg-brand-accent/10 rounded-2xl text-center border border-brand-accent/20">
            <p class="text-[10px] text-brand-accent font-black uppercase tracking-[0.2em] mb-2">Dukungan Aktif</p>
            <p class="text-xs text-brand-accent/80 font-medium">KedaiPOS Ecosystem v1.0</p>
        </div>
    </div>
</div>

<!-- Recent Transactions Table -->
<div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
    <div class="p-8 border-b border-slate-100 flex items-center justify-between">
        <h3 class="text-slate-900 font-extrabold text-xl">Transaksi Terbaru</h3>
        <a href="{{ route('admin.orders.index') }}" class="text-brand-primary font-bold text-sm hover:underline">Lihat Semua</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-slate-50">
                    <th class="px-8 py-4 text-slate-400 text-[10px] font-bold uppercase tracking-widest">ID Pesanan</th>
                    <th class="px-8 py-4 text-slate-400 text-[10px] font-bold uppercase tracking-widest">Meja</th>
                    <th class="px-8 py-4 text-slate-400 text-[10px] font-bold uppercase tracking-widest">Status</th>
                    <th class="px-8 py-4 text-slate-400 text-[10px] font-bold uppercase tracking-widest text-right">Total</th>
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
                                'completed' => ['bg-brand-secondary/10', 'text-brand-primary', 'SELESAI'],
                                'cancelled' => ['bg-slate-100', 'text-slate-500', 'DIBATALKAN'],
                                'ready' => ['bg-brand-secondary/10', 'text-brand-primary', 'SIAP'],
                                'default' => ['bg-brand-secondary/10', 'text-brand-primary', 'PENDING']
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
                    <td colspan="4" class="px-8 py-10 text-center text-slate-400 font-medium">Tidak ada transaksi tercatat hari ini.</td>
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
    gradient.addColorStop(0, 'rgba(157, 78, 221, 0.15)');
    gradient.addColorStop(1, 'rgba(157, 78, 221, 0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chart_data['labels']) !!},
            datasets: [{
                label: 'Pendapatan',
                data: {!! json_encode($chart_data['data']) !!},
                borderColor: '#9D4EDD',
                backgroundColor: gradient,
                borderWidth: 4,
                fill: true,
                tension: 0.4,
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
