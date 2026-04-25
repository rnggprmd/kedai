@extends('layouts.customer')

@section('title', 'Status Pesanan')

@section('content')
<div class="max-w-2xl mx-auto py-10 px-4">
    <!-- Status Header Section -->
    <div class="text-center mb-12">
        @php
            $statusConfig = match($order->status) {
                'pending' => ['icon' => 'bi-clock-history', 'color' => 'amber', 'label' => 'Menunggu Konfirmasi'],
                'confirmed' => ['icon' => 'bi-receipt-cutoff', 'color' => 'sky', 'label' => 'Tagihan Siap Bayar'],
                'completed' => ['icon' => 'bi-stars', 'color' => 'brand-primary', 'label' => 'Transaksi Selesai'],
                'cancelled' => ['icon' => 'bi-x-circle', 'color' => 'slate', 'label' => 'Pesanan Dibatalkan'],
                default => ['icon' => 'bi-receipt', 'color' => 'slate', 'label' => $order->status_label]
            };
        @endphp

        <!-- Animated Pulse Icon -->
        <div class="relative inline-flex mb-8">
            <div class="absolute inset-0 bg-brand-primary rounded-[2.5rem] blur-2xl opacity-10 animate-pulse"></div>
            <div class="relative w-24 h-24 bg-brand-primary rounded-[2rem] shadow-xl border border-brand-primary/10 flex items-center justify-center text-4xl text-brand-secondary">
                <i class="bi {{ $statusConfig['icon'] }}"></i>
            </div>
        </div>

        <h2 class="text-slate-900 font-extrabold text-3xl tracking-tight mb-2">{{ $statusConfig['label'] }}</h2>
        <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-slate-100 rounded-full text-slate-500 text-[10px] font-bold uppercase tracking-widest">
            Pesanan #{{ $order->kode_order }}
        </div>
    </div>

    <!-- Professional Step Progress -->
    <div class="relative flex justify-between items-center mb-16 px-4">
        <div class="absolute top-1/2 left-0 w-full h-1 bg-slate-100 -translate-y-1/2 -z-10 rounded-full"></div>
        @php
            $steps = [
                ['st' => 'pending', 'icon' => 'bi-receipt'],
                ['st' => 'confirmed', 'icon' => 'bi-wallet2'],
                ['st' => 'completed', 'icon' => 'bi-check-all']
            ];
            $currentIdx = array_search($order->status, array_column($steps, 'st'));
            if($order->status == 'preparing' || $order->status == 'ready') $currentIdx = 1; // Legacy handling
        @endphp

        @foreach($steps as $index => $step)
            @php
                $isCompleted = $currentIdx > $index || $order->status == 'completed';
                $isActive = $currentIdx === $index && $order->status != 'completed';
            @endphp
            <div class="relative flex flex-col items-center">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center transition-all duration-500 border-2 {{ $isActive ? 'bg-brand-primary border-brand-primary text-brand-secondary shadow-lg shadow-brand-primary/20 scale-125' : ($isCompleted ? 'bg-brand-secondary border-brand-secondary text-brand-primary' : 'bg-white border-slate-100 text-slate-300') }}">
                    <i class="bi {{ $step['icon'] }} text-lg"></i>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Order Detail Card -->
    <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-sm overflow-hidden mb-8">
        <div class="p-8 border-b border-slate-50 bg-slate-50/50">
            <h3 class="text-slate-900 font-extrabold text-lg flex items-center gap-3">
                <i class="bi bi-card-list text-brand-primary"></i> Ringkasan Pesanan
            </h3>
        </div>
        <div class="p-8 space-y-6">
            @foreach($order->items as $item)
            <div class="flex justify-between items-start gap-4">
                <div class="flex-1">
                    <div class="text-slate-900 font-bold text-base">{{ $item->nama_menu }}</div>
                    <div class="text-slate-400 text-xs font-medium">{{ $item->jumlah }}x @ {{ number_format($item->harga, 0, ',', '.') }}</div>
                    @if($item->catatan)
                        <div class="mt-1 text-[10px] text-brand-primary font-bold uppercase tracking-wider bg-brand-secondary px-2 py-0.5 rounded inline-block">
                            Catatan: {{ $item->catatan }}
                        </div>
                    @endif
                </div>
                <div class="text-slate-900 font-extrabold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
            </div>
            @endforeach

            <div class="pt-6 border-t border-slate-100 flex justify-between items-center">
                <div class="text-slate-400 text-xs font-bold uppercase tracking-[0.2em]">Total Pembayaran</div>
                <div class="text-slate-900 font-black text-2xl tracking-tight">{{ $order->formatted_total }}</div>
            </div>
        </div>
    </div>

    <!-- Midtrans Payment Button -->
    @if($order->snap_token && !in_array($order->status, ['completed', 'cancelled']))
    <div class="mb-8 animate-in slide-in-from-bottom-5 duration-700">
        <div class="bg-brand-primary border border-brand-primary/10 rounded-[2rem] p-6 text-center">
            <p class="text-brand-secondary font-bold text-sm mb-4">Amankan pesanan Anda dengan pembayaran digital</p>
            <button id="pay-button" class="w-full bg-brand-secondary text-brand-primary py-5 rounded-2xl font-black text-lg shadow-xl shadow-brand-secondary/10 hover:bg-white transition-all active:scale-95 flex items-center justify-center gap-3">
                <i class="bi bi-wallet2 text-xl"></i> 
                Bayar dengan Midtrans
            </button>
        </div>
    </div>
    @endif

    <!-- Floating Action Refresh -->
    <div class="text-center flex flex-col items-center gap-4">
        <button onclick="window.location.reload()" class="group inline-flex items-center gap-3 px-8 py-4 bg-white border border-slate-200 rounded-2xl text-slate-600 font-bold text-sm hover:bg-slate-900 hover:text-white hover:border-slate-900 transition-all shadow-sm hover:shadow-xl active:scale-95">
            <i class="bi bi-arrow-clockwise text-lg group-hover:rotate-180 transition-transform duration-500"></i>
            Perbarui Status
        </button>
        
        <div class="flex flex-col gap-3 w-full">
            @if($order->status == 'completed')
            <a href="{{ route('customer.order.invoice', ['qr_token' => $table->qr_token, 'order' => $order->id]) }}" 
               class="w-full bg-brand-primary text-brand-secondary py-5 rounded-2xl font-black text-center shadow-xl shadow-brand-primary/10 flex items-center justify-center gap-3 hover:scale-[1.02] transition-all active:scale-95">
                <i class="bi bi-receipt-cutoff text-xl"></i>
                Unduh Invoice PDF
            </a>
            @endif
            
            <a href="{{ route('customer.menu', $table->qr_token) }}" 
               class="w-full bg-white text-slate-400 py-5 rounded-2xl font-black text-center border-2 border-slate-100 hover:bg-slate-50 transition-all active:scale-95">
                Kembali ke Menu
            </a>
        </div>

        <div class="mt-8 pt-8 border-t border-slate-100 w-full flex flex-col items-center gap-4">
            <p class="text-slate-400 text-[10px] font-bold uppercase tracking-[0.2em]">
                Pengecekan otomatis setiap 30 detik • Pembaruan terakhir: {{ now()->format('H:i:s') }}
            </p>
        </div>
    </div>
</div>

<!-- Midtrans Snap JS -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script type="text/javascript">
    const payButton = document.getElementById('pay-button');
    if (payButton) {
        payButton.onclick = function() {
            window.snap.pay('{{ $order->snap_token }}', {
                onSuccess: function(result) {
                    window.location.reload();
                },
                onPending: function(result) {
                    window.location.reload();
                },
                onError: function(result) {
                    window.location.reload();
                },
                onClose: function() {
                    console.log('Customer closed the popup without finishing the payment');
                }
            });
        };
    }

    // Auto-refresh logic: Poll every 30 seconds unless completed/cancelled
    @if(!in_array($order->status, ['completed', 'cancelled']))
    setTimeout(() => {
        window.location.reload();
    }, 30000);
    @endif
</script>

@push('styles')
@endpush
@endsection
