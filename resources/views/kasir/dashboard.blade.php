@extends('layouts.kasir')

@section('title', 'Kontrol Layanan')

@section('content')
<!-- Kasir Welcome Banner (Premium Purple Theme) -->
<div class="relative overflow-hidden rounded-[2.5rem] p-10 lg:p-14 text-white shadow-2xl mb-12" style="background: linear-gradient(135deg, #240046 0%, #3C096C 100%);">
    <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-10">
        <div>
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
            [
                'label' => 'Antrean Pending', 
                'val' => $orders->where('status', 'pending')->count(), 
                'color' => 'bg-amber-500/10 text-amber-500', 
                'icon' => 'bi-clock-history',
                'meta' => 'Prioritas'
            ],
            [
                'label' => 'Menunggu Bayar', 
                'val' => $orders->where('status', 'confirmed')->count(), 
                'color' => 'bg-blue-500/10 text-blue-500', 
                'icon' => 'bi-receipt',
                'meta' => 'Aktif'
            ],
            [
                'label' => 'Pesanan Berhasil', 
                'val' => $orders->where('status', 'completed')->count(), 
                'color' => 'bg-emerald-500/10 text-emerald-500', 
                'icon' => 'bi-patch-check-fill',
                'meta' => 'Hari Ini'
            ],
            [
                'label' => 'Total Pendapatan', 
                'val' => 'Rp ' . number_format($orders->where('status', 'completed')->sum('total_harga'), 0, ',', '.'), 
                'color' => 'bg-brand-accent/10 text-brand-accent', 
                'icon' => 'bi-wallet2',
                'meta' => '+12.5%' // Dummy meta as in screenshot
            ],
        ];
    @endphp

    @foreach($kasirStats as $s)
    <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm hover:shadow-xl transition-all duration-500 group relative overflow-hidden">
        <div class="flex items-center justify-between mb-8">
            <div class="w-14 h-14 {{ $s['color'] }} rounded-2xl flex items-center justify-center text-2xl shadow-sm group-hover:scale-110 transition-transform duration-500">
                <i class="bi {{ $s['icon'] }}"></i>
            </div>
            <div class="text-slate-300 font-black text-[10px] uppercase tracking-[0.2em]">{{ $s['meta'] }}</div>
        </div>
        <div class="space-y-1">
            <div class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em]">{{ $s['label'] }}</div>
            <div class="text-slate-900 {{ str_contains($s['val'], 'Rp') ? 'text-2xl' : 'text-4xl' }} font-black tracking-tighter">{{ $s['val'] }}</div>
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

<!-- Recent Transactions Table -->
<div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden mb-12 transition-all hover:shadow-xl hover:shadow-slate-200/50">
    <div class="p-8 border-b border-slate-100 flex items-center justify-between">
        <h3 class="text-slate-900 font-extrabold text-xl tracking-tight">Transaksi Terbaru</h3>
        <a href="{{ route('kasir.orders.index') }}" class="text-brand-accent font-bold text-sm hover:underline">Lihat Semua</a>
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
                        <a href="{{ route('kasir.orders.show', $order) }}" class="inline-flex items-center justify-center w-9 h-9 bg-slate-50 text-slate-400 rounded-xl hover:bg-brand-accent hover:text-white transition-all shadow-sm">
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
