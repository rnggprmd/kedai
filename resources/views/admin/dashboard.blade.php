@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<!-- High-Impact Welcome Banner (Premium Purple Theme) -->
<div class="relative overflow-hidden rounded-[2.5rem] p-10 lg:p-14 text-white shadow-2xl mb-12" style="background: linear-gradient(135deg, #240046 0%, #3C096C 100%);">
    <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-10">
        <div>
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
    <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm hover:shadow-xl transition-all duration-500 group relative overflow-hidden">
        <div class="flex items-center justify-between mb-8">
            <div class="w-14 h-14 {{ $s['color'] }} rounded-2xl flex items-center justify-center text-2xl shadow-sm group-hover:scale-110 transition-transform duration-500">
                <i class="bi {{ $s['icon'] }}"></i>
            </div>
            <div class="text-slate-300 font-black text-[10px] uppercase tracking-[0.2em]">{{ $s['trend'] }}</div>
        </div>
        <div class="space-y-1">
            <div class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em]">{{ $s['label'] }}</div>
            <div class="text-slate-900 text-3xl font-black tracking-tighter">{{ $s['val'] }}</div>
        </div>
        <!-- Subtle Background Decoration -->
        <div class="absolute -right-4 -bottom-4 w-24 h-24 {{ explode(' ', $s['color'])[0] }} opacity-[0.03] rounded-full group-hover:scale-150 transition-transform duration-1000"></div>
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

    <!-- Staff & Actions Bento (Premium Dark Theme) -->
    <div class="bg-brand-primary p-10 rounded-[3rem] text-white shadow-2xl flex flex-col relative overflow-hidden">
        <div class="relative z-10">
            <h3 class="font-black text-2xl tracking-tight mb-8">Kontrol Cepat</h3>
            
            <div class="space-y-4 flex-1">
                {{-- Quick Link: Kelola Menu --}}
                <a href="{{ route('admin.menus.index') }}" class="group flex items-center gap-4 p-5 bg-white/10 hover:bg-white rounded-[1.5rem] border border-white/20 hover:border-white transition-all">
                    <div class="w-12 h-12 bg-brand-secondary text-brand-primary rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform shadow-lg shadow-brand-secondary/20">
                        <i class="bi bi-journal-text text-xl"></i>
                    </div>
                    <div>
                        <div class="font-black text-sm group-hover:text-slate-900 transition-colors">Kelola Menu</div>
                        <div class="text-white/60 group-hover:text-slate-400 text-[10px] font-bold uppercase tracking-widest mt-0.5">Atur Katalog</div>
                    </div>
                </a>

                {{-- Quick Link: Laporan --}}
                <a href="{{ route('admin.reports.index') }}" class="group flex items-center gap-4 p-5 bg-white/5 hover:bg-white/10 rounded-[1.5rem] border border-white/10 transition-all">
                    <div class="w-12 h-12 bg-white/20 text-white rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="bi bi-bar-chart-fill text-xl"></i>
                    </div>
                    <div>
                        <div class="font-black text-sm">Pusat Laporan</div>
                        <div class="text-white/60 text-[10px] font-bold uppercase tracking-widest mt-0.5">Analitik Bisnis</div>
                    </div>
                </a>

                {{-- Quick Info: Staff --}}
                <div class="flex items-center gap-4 p-5 bg-white/5 rounded-[1.5rem] border border-white/5 border-dashed">
                    <div class="w-12 h-12 bg-white/10 text-white/40 rounded-xl flex items-center justify-center">
                        <i class="bi bi-people-fill text-xl"></i>
                    </div>
                    <div>
                        <div class="font-black text-sm text-white/90">{{ $stats['total_kasir'] }} Staff</div>
                        <div class="text-white/40 text-[10px] font-bold uppercase tracking-widest mt-0.5">Tim Kasir Aktif</div>
                    </div>
                </div>
            </div>

            <div class="mt-12 p-6 bg-brand-accent/20 rounded-2xl border border-brand-accent/30 shadow-lg shadow-brand-accent/10">
                <p class="text-xs text-white font-medium leading-relaxed italic">
                    "Kecepatan adalah jantung dari keramah-tamahan."
                </p>
            </div>
        </div>
        <!-- Decorative Shape -->
        <div class="absolute -bottom-20 -right-20 w-48 h-48 bg-white/5 rounded-full blur-3xl"></div>
    </div>
</div>

<!-- Recent Transactions Table -->
<div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden mb-12 transition-all hover:shadow-xl hover:shadow-slate-200/50">
    <div class="p-8 border-b border-slate-100 flex items-center justify-between">
        <h3 class="text-slate-900 font-extrabold text-xl tracking-tight">Transaksi Terbaru</h3>
        <a href="{{ route('admin.orders.index') }}" class="text-brand-primary font-bold text-sm hover:underline">Lihat Semua</a>
    </div>
    <div class="overflow-x-auto max-h-[450px] overflow-y-auto">
        <table class="w-full text-left border-collapse">
            <thead class="sticky top-0 z-10 bg-white/95 backdrop-blur-sm">
                <tr class="bg-slate-50/50">
                    <th class="px-8 py-4 text-slate-400 text-[10px] font-black uppercase tracking-[0.2em]">ID Pesanan</th>
                    <th class="px-8 py-4 text-slate-400 text-[10px] font-black uppercase tracking-[0.2em]">Pelanggan & Meja</th>
                    <th class="px-8 py-4 text-slate-400 text-[10px] font-black uppercase tracking-[0.2em]">Status</th>
                    <th class="px-8 py-4 text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] text-right">Total</th>
                    <th class="px-8 py-4 text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($recent_orders as $order)
                <tr class="hover:bg-slate-50/50 transition-colors group">
                    <td class="px-8 py-5">
                        <div class="text-slate-900 font-black text-sm tracking-tight">#{{ $order->kode_order }}</div>
                        <div class="text-slate-400 text-[10px] font-bold uppercase mt-0.5">{{ $order->created_at->format('H:i') }} WIB</div>
                    </td>
                    <td class="px-8 py-5">
                        <div class="text-slate-900 font-black text-sm tracking-tight leading-none mb-1">{{ $order->nama_pelanggan ?? 'Walk-in' }}</div>
                        <div class="text-brand-accent text-[10px] font-bold uppercase tracking-widest">{{ $order->table->nama_meja }}</div>
                    </td>
                    <td class="px-8 py-5">
                        @php
                            $statusMap = [
                                'completed' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-500', 'label' => 'Selesai'],
                                'confirmed' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-500', 'label' => 'Menunggu Bayar'],
                                'pending' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-500', 'label' => 'Pending'],
                                'cancelled' => ['bg' => 'bg-red-50', 'text' => 'text-red-500', 'label' => 'Batal'],
                            ];
                            $s = $statusMap[$order->status] ?? ['bg' => 'bg-slate-50', 'text' => 'text-slate-400', 'label' => strtoupper($order->status)];
                        @endphp
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 {{ $s['bg'] }} {{ $s['text'] }} rounded-full text-[9px] font-black uppercase tracking-widest border border-current/10">
                            <span class="w-1 h-1 rounded-full bg-current"></span> {{ $s['label'] }}
                        </span>
                    </td>
                    <td class="px-8 py-5 text-right font-black text-slate-900">
                        {{ $order->formatted_total }}
                    </td>
                    <td class="px-8 py-5 text-right">
                        <a href="{{ route('admin.orders.show', $order) }}" class="inline-flex items-center justify-center w-9 h-9 bg-slate-50 text-slate-400 rounded-xl hover:bg-brand-accent hover:text-white transition-all shadow-sm">
                            <i class="bi bi-eye-fill"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-8 py-10 text-center">
                        <p class="text-slate-400 font-bold text-sm italic">Belum ada transaksi hari ini.</p>
                    </td>
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
