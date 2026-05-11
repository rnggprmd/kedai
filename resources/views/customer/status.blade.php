@extends('layouts.customer')

@section('title', 'Status Pesanan')

@section('content')
<div class="relative lg:fixed lg:inset-x-0 lg:bottom-0 lg:top-[80px] bg-[#f8f9ff] min-h-screen lg:min-h-0 flex flex-col overflow-y-auto lg:overflow-hidden">
    <div class="max-w-6xl mx-auto w-full h-auto lg:h-full px-4 lg:px-12 py-4 lg:py-6 flex flex-col gap-4 overflow-visible lg:overflow-hidden">
        
        <!-- Premium Status Header (Slimmer) -->
        <div class="bg-slate-900 rounded-[2rem] p-6 lg:p-8 shadow-md relative overflow-hidden shrink-0 border border-white/5">
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-brand-secondary/10 rounded-full blur-[80px]"></div>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 lg:gap-6 relative z-10">
                <div class="flex items-center gap-4 lg:gap-6">
                    @php
                        $statusConfig = match($order->status) {
                            'pending' => ['icon' => 'bi-hourglass-split', 'label' => 'Pesanan Diterima', 'color' => 'text-brand-secondary', 'bg' => 'bg-brand-secondary/10'],
                            'confirmed' => ['icon' => 'bi-fire', 'label' => 'Sedang Diproses', 'color' => 'text-brand-accent', 'bg' => 'bg-brand-accent/10'],
                            'completed' => ['icon' => 'bi-stars', 'label' => 'Selamat Menikmati!', 'color' => 'text-emerald-400', 'bg' => 'bg-emerald-400/10'],
                            'cancelled' => ['icon' => 'bi-x-circle', 'label' => 'Pesanan Dibatalkan', 'color' => 'text-rose-400', 'bg' => 'bg-rose-400/10'],
                            default => ['icon' => 'bi-receipt', 'label' => $order->status_label, 'color' => 'text-slate-400', 'bg' => 'bg-slate-400/10']
                        };
                    @endphp
                    <div class="w-12 h-12 lg:w-16 lg:h-16 {{ $statusConfig['bg'] }} {{ $statusConfig['color'] }} rounded-xl lg:rounded-2xl flex items-center justify-center text-2xl lg:text-3xl shrink-0 border border-white/5">
                        <i class="bi {{ $statusConfig['icon'] }}"></i>
                    </div>
                    <div>
                        <h2 class="text-white font-black text-xl lg:text-3xl tracking-tight leading-none mb-2">{{ $statusConfig['label'] }}</h2>
                        <div class="flex items-center gap-2 lg:gap-3">
                            <span class="px-1.5 py-0.5 bg-white/5 rounded text-slate-400 font-bold text-[8px] lg:text-[9px] uppercase tracking-widest border border-white/5">#{{ $order->kode_order }}</span>
                            <span class="text-slate-400 font-bold text-[9px] lg:text-[10px]">Meja {{ $order->table->nama_meja }}</span>
                        </div>
                    </div>
                </div>

                <!-- Slim Timeline -->
                <div class="flex items-center gap-2 px-4 py-3 bg-white/5 rounded-2xl border border-white/5 backdrop-blur-sm">
                    @foreach(['Dipesan', 'Proses', 'Selesai'] as $i => $l)
                        @php $isComp = (array_search($order->status, ['pending','confirmed','completed']) >= $i) || $order->status == 'completed'; @endphp
                        <div class="flex flex-col items-center gap-1.5">
                            <div class="w-1.5 h-1.5 rounded-full {{ $isComp ? 'bg-brand-secondary' : 'bg-slate-700' }}"></div>
                            <span class="text-[7px] font-black uppercase tracking-widest {{ $isComp ? 'text-brand-secondary' : 'text-slate-600' }}">{{ $l }}</span>
                        </div>
                        @if($i < 2) <div class="w-6 h-px bg-slate-800"></div> @endif
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Dashboard Grid (Scrollable on Mobile, Fixed on Desktop) -->
        <div class="lg:flex-1 lg:min-h-0 grid grid-cols-1 lg:grid-cols-3 gap-4 overflow-visible lg:overflow-hidden custom-scrollbar pb-20 lg:pb-0">
            <!-- Left: Digital Receipt -->
            <div class="lg:col-span-2 bg-white rounded-[2rem] border border-slate-200 shadow-sm flex flex-col overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-50 bg-slate-50/50 flex items-center justify-between shrink-0">
                    <h3 class="text-slate-900 font-black text-[10px] uppercase tracking-[0.2em] flex items-center gap-2">
                        <i class="bi bi-receipt-cutoff text-brand-primary"></i> Rincian Pesanan
                    </h3>
                </div>
                
                <div class="flex-1 overflow-y-auto p-6 custom-scrollbar space-y-4">
                    @foreach($order->items as $item)
                    <div class="flex justify-between items-center group">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-slate-900 text-brand-secondary rounded-xl flex items-center justify-center font-black text-xs shrink-0">
                                {{ $item->jumlah }}
                            </div>
                            <div>
                                <h4 class="text-slate-900 font-black text-sm">{{ $item->nama_menu }}</h4>
                                <p class="text-slate-400 text-[10px] font-bold">@ {{ number_format($item->harga, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        <div class="text-slate-900 font-black text-base tracking-tight">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
 
            <!-- Right: Summary & Actions -->
            <div class="flex flex-col gap-4 overflow-hidden lg:h-full">
                @if($order->status == 'completed' && $order->payment)
                <!-- LUNAS SUCCESS CARD (Ultra-Minimalist & Stretched) -->
                <div class="lg:flex-1 h-full lg:h-auto bg-white rounded-[2.5rem] border border-slate-100 shadow-sm flex flex-col items-center justify-center p-4 lg:p-6 text-center animate-in zoom-in duration-500">
                    <div class="w-12 h-12 bg-brand-secondary text-white rounded-full flex items-center justify-center mb-3 relative shadow-md shadow-brand-secondary/20 shrink-0">
                        <i class="bi bi-patch-check-fill text-xl"></i>
                    </div>
                    
                    <h2 class="text-slate-900 font-black text-lg mb-0.5 tracking-tight uppercase">Lunas</h2>
                    <p class="text-brand-secondary font-black text-[7px] tracking-[0.2em] uppercase mb-4">Transaksi Terverifikasi</p>

                    <div class="w-full max-w-[240px] space-y-1.5">
                        <div class="flex items-center justify-between p-2.5 bg-slate-50/50 rounded-lg border border-slate-100/50">
                            <span class="text-[7px] font-black text-slate-400 uppercase tracking-widest">Metode</span>
                            <span class="text-[9px] font-black text-slate-900 uppercase tracking-tight">{{ $order->payment->metode ?? 'Digital' }}</span>
                        </div>
                        <div class="flex items-center justify-between p-2.5 bg-slate-50/50 rounded-lg border border-slate-100/50">
                            <span class="text-[7px] font-black text-slate-400 uppercase tracking-widest">Tunai Diterima</span>
                            <span class="text-[9px] font-black text-slate-900 tracking-tight">Rp {{ number_format($order->payment->jumlah_bayar, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex items-center justify-between p-2.5 bg-brand-primary/10 rounded-lg border border-brand-primary/20">
                            <span class="text-brand-primary text-[7px] font-black uppercase tracking-widest">Kembalian</span>
                            <span class="text-brand-primary text-[9px] font-black tracking-tight">Rp {{ number_format($order->payment->jumlah_kembali, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex items-center justify-between p-2.5 bg-slate-50/50 rounded-lg border border-slate-100/50">
                            <span class="text-[7px] font-black text-slate-400 uppercase tracking-widest">Waktu</span>
                            <span class="text-[9px] font-black text-slate-900 tracking-tight">{{ $order->payment->paid_at ? $order->payment->paid_at->format('H:i') : now()->format('H:i') }} WIB</span>
                        </div>
                    </div>

                    <a href="{{ route('customer.menu', $table->qr_token) }}" 
                       class="mt-5 w-full max-w-[240px] py-2.5 bg-slate-900 text-white rounded-lg font-black text-[8px] uppercase tracking-[0.2em] hover:bg-brand-primary transition-all active:scale-95 flex items-center justify-center gap-2 shrink-0">
                        <i class="bi bi-plus-lg"></i>
                        Pesan Menu Lagi
                    </a>
                </div>
                @else
                <!-- Normal Status Summary -->
                <div class="bg-slate-900 rounded-[2rem] p-6 text-white shadow-md relative overflow-hidden shrink-0 border border-white/5">
                    <p class="text-slate-400 text-[9px] font-black uppercase tracking-[0.3em] mb-1">Total Tagihan</p>
                    <div class="text-2xl font-black text-brand-secondary tracking-tighter mb-5">{{ $order->formatted_total }}</div>
                    
                    @if($order->snap_token && !in_array($order->status, ['completed', 'cancelled']))
                    <button id="pay-button" class="w-full bg-brand-secondary text-brand-primary py-3.5 rounded-xl font-black text-[10px] uppercase tracking-[0.2em] shadow-sm hover:brightness-110 active:scale-95 transition-all flex items-center justify-center gap-2">
                        <i class="bi bi-lightning-charge-fill"></i> Bayar Sekarang
                    </button>
                    @else
                    <div class="w-full py-3 px-4 bg-white/5 border border-white/10 rounded-xl text-center">
                        <span class="text-[9px] font-black uppercase tracking-widest text-emerald-400">Pembayaran Aman</span>
                    </div>
                    @endif
                </div>
 
                <!-- Action Menu -->
                <div class="flex-1 bg-white rounded-[2rem] border border-slate-200 p-6 flex flex-col gap-3 shadow-sm overflow-hidden">
                    <button onclick="window.location.reload()" class="w-full py-3.5 bg-slate-50 border border-slate-100 rounded-xl flex items-center justify-between px-5 text-slate-600 hover:bg-slate-900 hover:text-white transition-all group active:scale-95 shrink-0">
                        <span class="font-black text-[9px] uppercase tracking-[0.2em]">Cek Status</span>
                        <i class="bi bi-arrow-clockwise group-hover:rotate-180 transition-transform duration-700"></i>
                    </button>
                    
                    <a href="{{ route('customer.menu', $table->qr_token) }}" 
                       class="w-full py-3.5 bg-white border border-slate-100 rounded-xl flex items-center justify-between px-5 text-slate-400 hover:border-brand-primary hover:text-brand-primary transition-all active:scale-95 shrink-0">
                        <span class="font-black text-[9px] uppercase tracking-[0.2em]">Tambah Menu</span>
                        <i class="bi bi-plus-circle-fill"></i>
                    </a>
 
                    <div class="mt-auto pt-4 text-center border-t border-slate-50">
                        <p class="text-slate-300 text-[8px] font-bold uppercase tracking-[0.3em]">Kedai Wasis &copy; {{ now()->year }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #f1f5f9; border-radius: 10px; }
</style>

<!-- Midtrans Snap JS -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script type="text/javascript">
    const payButton = document.getElementById('pay-button');
    if (payButton) {
        payButton.onclick = function() {
            window.snap.pay('{{ $order->snap_token }}', {
                onSuccess: function(result) { 
                    showToast('Pembayaran Berhasil! Mengupdate status...', 'success');
                    setTimeout(() => { window.location.reload(); }, 2000);
                },
                onPending: function(result) { 
                    showToast('Menunggu pembayaran Anda...', 'success');
                    setTimeout(() => { window.location.reload(); }, 2000);
                },
                onError: function(result) { 
                    showToast('Gagal memproses pembayaran.', 'rose');
                },
                onClose: function() {
                    showToast('Pembayaran dibatalkan.', 'rose');
                }
            });
        };
    }
    @if(!in_array($order->status, ['completed', 'cancelled']))
    setTimeout(() => { window.location.reload(); }, 30000);
    @endif
</script>
@endsection
