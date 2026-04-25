@extends('layouts.kasir')

@section('title', 'Kontrol Layanan')

@section('content')
<!-- Kasir Welcome Banner (Premium Purple Theme) -->
<div class="relative overflow-hidden rounded-[2.5rem] p-10 lg:p-14 text-white shadow-2xl mb-12" style="background: linear-gradient(135deg, #240046 0%, #3C096C 100%);">
    <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-10">
        <div>
            <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-brand-primary/20 border border-brand-primary/30 rounded-full text-[10px] font-black uppercase tracking-widest mb-6">
                <span class="w-1.5 h-1.5 bg-brand-secondary rounded-full animate-ping"></span> Layanan Langsung Aktif
            </div>
            <h2 class="text-4xl lg:text-6xl font-black tracking-tighter mb-4 leading-none">
                Halo, {{ explode(' ', auth()->user()->name)[0] }}! <span class="text-brand-secondary">.</span>
            </h2>
            <p class="text-white/70 text-lg font-medium max-w-md">
                Optimalkan layanan Anda dan berikan kepuasan kepada pelanggan hari ini.
            </p>
        </div>
        <div class="flex items-center gap-6">
            <div class="bg-white/5 backdrop-blur-xl rounded-[2rem] p-8 border border-white/10 shadow-2xl">
                <div class="text-slate-500 text-[10px] font-black uppercase tracking-widest mb-3">Antrean Pesanan Langsung</div>
                <div class="flex items-center gap-4">
                    <div class="w-4 h-4 bg-brand-secondary rounded-full shadow-[0_0_20px_#FFD60A]"></div>
                    <div class="font-black text-4xl tracking-tighter">{{ $orders->whereIn('status', ['pending', 'preparing'])->count() }} <span class="text-slate-500 text-sm font-bold uppercase tracking-widest ml-1">Aktif</span></div>
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
        $kasirStats = [
            ['label' => 'Masuk', 'val' => $orders->where('status', 'pending')->count(), 'color' => 'bg-amber-500/10 text-amber-500', 'icon' => 'bi-hourglass-split'],
            ['label' => 'Diproses', 'val' => $orders->where('status', 'preparing')->count(), 'color' => 'bg-brand-primary/10 text-brand-primary', 'icon' => 'bi-fire'],
            ['label' => 'Siap', 'val' => $orders->where('status', 'ready')->count(), 'color' => 'bg-brand-secondary/10 text-brand-primary', 'icon' => 'bi-bell-fill'],
            ['label' => 'Pendapatan', 'val' => number_format($orders->where('status', 'completed')->sum('total_harga')/1000, 1) . 'k', 'color' => 'bg-brand-accent/10 text-brand-accent', 'icon' => 'bi-cash-stack'],
        ];
    @endphp

    @foreach($kasirStats as $s)
    <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm hover:shadow-xl transition-all duration-500 group">
        <div class="flex items-center justify-between mb-6">
            <div class="w-14 h-14 {{ $s['color'] }} rounded-2xl flex items-center justify-center text-2xl shadow-sm group-hover:scale-110 transition-transform">
                <i class="bi {{ $s['icon'] }}"></i>
            </div>
            <div class="text-slate-300 font-black text-xs uppercase tracking-widest">{{ $s['label'] }}</div>
        </div>
        <div class="text-slate-900 text-4xl font-black tracking-tighter">{{ $s['val'] }}</div>
    </div>
    @endforeach
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
    <!-- Visual Performance Bento -->
    <div class="lg:col-span-2 bg-white p-10 rounded-[3rem] border border-slate-200 shadow-sm">
        <div class="flex items-center justify-between mb-10">
            <div>
                <h3 class="text-slate-900 font-black text-2xl tracking-tight">Intensitas Layanan</h3>
                <p class="text-slate-400 text-sm font-medium">Memantau jam puncak pesanan.</p>
            </div>
            <div class="flex gap-2">
                <span class="w-3 h-3 bg-brand-primary rounded-full"></span>
                <span class="w-3 h-3 bg-slate-100 rounded-full"></span>
            </div>
        </div>
        <div class="h-[320px]">
            <canvas id="loadChart"></canvas>
        </div>
    </div>

    <!-- Quick Action Bento -->
    <div class="bg-brand-primary p-10 rounded-[3rem] text-white shadow-2xl flex flex-col relative overflow-hidden">
        <div class="relative z-10">
            <h3 class="font-black text-2xl tracking-tight mb-8">Aksi Cepat</h3>
            
            <div class="space-y-4 flex-1">
                <a href="{{ route('kasir.orders.create') }}" class="group flex items-center gap-4 p-5 bg-white/10 hover:bg-white rounded-[1.5rem] border border-white/20 hover:border-white transition-all">
                    <div class="w-12 h-12 bg-brand-secondary text-brand-primary rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="bi bi-plus-circle-fill text-xl"></i>
                    </div>
                    <div>
                        <div class="font-black text-sm group-hover:text-slate-900 transition-colors">Buat Pesanan</div>
                        <div class="text-white/60 group-hover:text-slate-400 text-[10px] font-bold uppercase tracking-widest mt-0.5">Transaksi Baru</div>
                    </div>
                </a>

                <a href="{{ route('kasir.orders.index') }}" class="group flex items-center gap-4 p-5 bg-white/5 hover:bg-white/10 rounded-[1.5rem] border border-white/10 transition-all">
                    <div class="w-12 h-12 bg-white/20 text-white rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="bi bi-receipt text-xl"></i>
                    </div>
                    <div>
                        <div class="font-black text-sm">Antrean Pesanan</div>
                        <div class="text-white/60 text-[10px] font-bold uppercase tracking-widest mt-0.5">Kelola Layanan</div>
                    </div>
                </a>
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

@push('scripts')
<script>
    const ctxLoad = document.getElementById('loadChart').getContext('2d');
    new Chart(ctxLoad, {
        type: 'bar',
        data: {
            labels: {!! json_encode($hourly_data['labels']) !!},
            datasets: [{
                label: 'Pesanan',
                data: {!! json_encode($hourly_data['data']) !!},
                backgroundColor: '#9D4EDD',
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
