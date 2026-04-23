@extends('layouts.customer')

@section('title', 'Order Status')

@section('content')
<div class="max-w-2xl mx-auto py-10 px-4">
    <!-- Status Header Section -->
    <div class="text-center mb-12">
        @php
            $statusConfig = match($order->status) {
                'pending' => ['icon' => 'bi-clock-history', 'color' => 'amber', 'label' => 'Waiting for Confirmation'],
                'confirmed' => ['icon' => 'bi-check2-circle', 'color' => 'sky', 'label' => 'Order Confirmed'],
                'preparing' => ['icon' => 'bi-fire', 'color' => 'rose', 'label' => 'Chef is Cooking'],
                'ready' => ['icon' => 'bi-bell-fill', 'color' => 'emerald', 'label' => 'Ready to Serve'],
                'completed' => ['icon' => 'bi-stars', 'color' => 'indigo', 'label' => 'Enjoy your Meal!'],
                'cancelled' => ['icon' => 'bi-x-circle', 'color' => 'slate', 'label' => 'Order Cancelled'],
                default => ['icon' => 'bi-receipt', 'color' => 'slate', 'label' => $order->status_label]
            };
        @endphp

        <!-- Animated Pulse Icon -->
        <div class="relative inline-flex mb-8">
            <div class="absolute inset-0 bg-{{ $statusConfig['color'] }}-400 rounded-[2.5rem] blur-2xl opacity-20 animate-pulse"></div>
            <div class="relative w-24 h-24 bg-white rounded-[2rem] shadow-xl border border-{{ $statusConfig['color'] }}-100 flex items-center justify-center text-4xl text-{{ $statusConfig['color'] }}-600">
                <i class="bi {{ $statusConfig['icon'] }}"></i>
            </div>
        </div>

        <h2 class="text-slate-900 font-extrabold text-3xl tracking-tight mb-2">{{ $statusConfig['label'] }}</h2>
        <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-slate-100 rounded-full text-slate-500 text-[10px] font-bold uppercase tracking-widest">
            Order #{{ $order->kode_order }}
        </div>
    </div>

    <!-- Professional Step Progress -->
    <div class="relative flex justify-between items-center mb-16 px-4">
        <div class="absolute top-1/2 left-0 w-full h-1 bg-slate-100 -translate-y-1/2 -z-10 rounded-full"></div>
        @php
            $steps = [
                ['st' => 'pending', 'icon' => 'bi-receipt'],
                ['st' => 'preparing', 'icon' => 'bi-fire'],
                ['st' => 'ready', 'icon' => 'bi-bell'],
                ['st' => 'completed', 'icon' => 'bi-check-all']
            ];
            $currentIdx = array_search($order->status, array_column($steps, 'st'));
            if($order->status == 'confirmed') $currentIdx = 0; // Confirmed is still early
        @endphp

        @foreach($steps as $index => $step)
            @php
                $isCompleted = $currentIdx > $index || $order->status == 'completed';
                $isActive = $currentIdx === $index && $order->status != 'completed';
            @endphp
            <div class="relative flex flex-col items-center">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center transition-all duration-500 border-2 {{ $isActive ? 'bg-indigo-600 border-indigo-600 text-white shadow-lg shadow-indigo-200 scale-125' : ($isCompleted ? 'bg-slate-900 border-slate-900 text-white' : 'bg-white border-slate-100 text-slate-300') }}">
                    <i class="bi {{ $step['icon'] }} text-lg"></i>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Order Detail Card -->
    <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-sm overflow-hidden mb-8">
        <div class="p-8 border-b border-slate-50 bg-slate-50/50">
            <h3 class="text-slate-900 font-extrabold text-lg flex items-center gap-3">
                <i class="bi bi-card-list text-indigo-600"></i> Order Summary
            </h3>
        </div>
        <div class="p-8 space-y-6">
            @foreach($order->items as $item)
            <div class="flex justify-between items-start gap-4">
                <div class="flex-1">
                    <div class="text-slate-900 font-bold text-base">{{ $item->nama_menu }}</div>
                    <div class="text-slate-400 text-xs font-medium">{{ $item->jumlah }}x @ {{ number_format($item->harga, 0, ',', '.') }}</div>
                    @if($item->catatan)
                        <div class="mt-1 text-[10px] text-amber-600 font-bold uppercase tracking-wider bg-amber-50 px-2 py-0.5 rounded inline-block">
                            Note: {{ $item->catatan }}
                        </div>
                    @endif
                </div>
                <div class="text-slate-900 font-extrabold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
            </div>
            @endforeach

            <div class="pt-6 border-t border-slate-100 flex justify-between items-center">
                <div class="text-slate-400 text-xs font-bold uppercase tracking-[0.2em]">Total Amount</div>
                <div class="text-slate-900 font-black text-2xl tracking-tight">{{ $order->formatted_total }}</div>
            </div>
        </div>
    </div>

    <!-- Floating Action Refresh -->
    <div class="text-center flex flex-col items-center gap-4">
        <button onclick="window.location.reload()" class="group inline-flex items-center gap-3 px-8 py-4 bg-white border border-slate-200 rounded-2xl text-slate-600 font-bold text-sm hover:bg-slate-900 hover:text-white hover:border-slate-900 transition-all shadow-sm hover:shadow-xl active:scale-95">
            <i class="bi bi-arrow-clockwise text-lg group-hover:rotate-180 transition-transform duration-500"></i>
            Refresh Status
        </button>
        
        <a href="{{ route('customer.menu', $table->qr_token) }}" class="inline-flex items-center gap-2 text-indigo-600 font-bold text-xs uppercase tracking-widest hover:text-indigo-800 transition-colors">
            <i class="bi bi-plus-circle"></i> Want to order more?
        </a>

        <p class="mt-4 text-slate-400 text-[10px] font-bold uppercase tracking-[0.2em]">
            Auto-checking every 30s • Last update: {{ now()->format('H:i:s') }}
        </p>
    </div>
</div>

<script>
    // Auto-refresh logic: Poll every 30 seconds unless completed/cancelled
    @if(!in_array($order->status, ['completed', 'cancelled']))
    setTimeout(() => {
        window.location.reload();
    }, 30000);
    @endif
</script>

@push('styles')
<style>
    /* Specific dynamic color safelisting just in case JIT missed them */
    .bg-amber-400 { background-color: #fbbf24; }
    .bg-rose-400 { background-color: #fb7185; }
    .bg-emerald-400 { background-color: #34d399; }
    .bg-sky-400 { background-color: #38bdf8; }
</style>
@endpush
@endsection
