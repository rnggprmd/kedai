@extends('layouts.kasir')

@section('title', 'Order Ticket')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-10">
    <div class="lg:col-span-2 space-y-8">
        <!-- Kitchen Progress Control -->
        <div class="bg-white p-8 lg:p-10 rounded-[2.5rem] border border-slate-200 shadow-sm">
            <h3 class="text-slate-900 font-extrabold text-xl mb-8 flex items-center gap-3">
                <i class="bi bi-fire text-orange-500"></i> Service Status Control
            </h3>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 lg:gap-4">
                @php
                    $steps = [
                        ['status' => 'pending', 'label' => 'Pending', 'icon' => 'bi-clock', 'color' => 'amber'],
                        ['status' => 'preparing', 'label' => 'Kitchen', 'icon' => 'bi-fire', 'color' => 'indigo'],
                        ['status' => 'ready', 'label' => 'Ready', 'icon' => 'bi-bell', 'color' => 'sky'],
                        ['status' => 'completed', 'label' => 'Done', 'icon' => 'bi-check-circle', 'color' => 'emerald'],
                    ];
                @endphp
                @foreach($steps as $step)
                    <form action="{{ route('kasir.orders.updateStatus', $order) }}" method="POST" class="w-full">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="{{ $step['status'] }}">
                        <button type="submit" 
                                class="w-full py-4 rounded-2xl font-extrabold border-2 transition-all flex flex-col items-center gap-2 group
                                {{ $order->status == $step['status'] 
                                    ? "bg-{$step['color']}-600 border-{$step['color']}-600 text-white shadow-lg shadow-{$step['color']}-200" 
                                    : 'bg-white border-slate-100 text-slate-400 hover:border-slate-300 hover:text-slate-600' }}"
                                {{ ($order->status == 'completed' || $order->status == 'cancelled') && $order->status != $step['status'] ? 'disabled' : '' }}>
                            <i class="bi {{ $step['icon'] }} text-xl group-hover:scale-110 transition-transform"></i>
                            <span class="text-[10px] uppercase tracking-widest">{{ $step['label'] }}</span>
                        </button>
                    </form>
                @endforeach
            </div>
        </div>

        <!-- Order Items Detail -->
        <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-8 lg:p-10 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-slate-900 font-extrabold text-xl">Order Items</h3>
                <span class="bg-slate-900 text-white text-[10px] font-extrabold px-4 py-2 rounded-full tracking-widest">TABLE {{ $order->table->nama_meja }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50">
                            <th class="px-8 py-4 text-slate-400 text-[10px] font-bold uppercase tracking-widest">Dish Name</th>
                            <th class="px-8 py-4 text-slate-400 text-[10px] font-bold uppercase tracking-widest text-center">Qty</th>
                            <th class="px-8 py-4 text-slate-400 text-[10px] font-bold uppercase tracking-widest text-right">Price</th>
                            <th class="px-8 py-4 text-slate-400 text-[10px] font-bold uppercase tracking-widest text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($order->items as $item)
                        <tr>
                            <td class="px-8 py-6">
                                <div class="text-slate-900 font-extrabold text-base">{{ $item->nama_menu }}</div>
                                @if($item->catatan)
                                    <div class="text-slate-400 text-xs italic mt-1 font-medium">"{{ $item->catatan }}"</div>
                                @endif
                            </td>
                            <td class="px-8 py-6 text-center text-slate-900 font-extrabold">{{ $item->jumlah }}</td>
                            <td class="px-8 py-6 text-right text-slate-400 font-bold text-sm">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                            <td class="px-8 py-6 text-right text-slate-900 font-extrabold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-slate-900 text-white">
                            <td colspan="3" class="px-8 py-6 font-bold uppercase tracking-widest text-xs opacity-60">Grand Total</td>
                            <td class="px-8 py-6 text-right font-extrabold text-2xl tracking-tighter">{{ $order->formatted_total }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Payment & Info Sidebar -->
    <div class="space-y-6 lg:space-y-8">
        <!-- Payment Module -->
        <div class="bg-white p-8 lg:p-10 rounded-[2.5rem] border border-slate-200 shadow-sm">
            <h3 class="text-slate-900 font-extrabold text-xl mb-8">Payment Console</h3>
            
            @if($order->status == 'completed')
                <div class="text-center p-8 bg-emerald-50 rounded-[2rem] border border-emerald-100">
                    <div class="w-16 h-16 bg-emerald-500 text-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg shadow-emerald-200">
                        <i class="bi bi-patch-check-fill text-3xl"></i>
                    </div>
                    <h4 class="text-emerald-900 font-extrabold text-lg mb-1 uppercase tracking-tight">Fully Paid</h4>
                    <p class="text-emerald-600/70 text-[10px] font-bold uppercase tracking-widest">Via {{ $order->payment->metode ?? 'Cash' }} • {{ $order->payment->created_at->format('H:i') }} WIB</p>
                </div>
            @elseif($order->status == 'cancelled')
                <div class="text-center p-8 bg-rose-50 rounded-[2rem] border border-rose-100">
                    <div class="w-16 h-16 bg-rose-500 text-white rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="bi bi-x-circle-fill text-3xl"></i>
                    </div>
                    <h4 class="text-rose-900 font-extrabold text-lg uppercase tracking-tight">Cancelled</h4>
                </div>
            @else
                <form action="{{ route('kasir.orders.pay', $order) }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-3 ml-1">Payment Method</label>
                        <select name="metode" class="w-full bg-slate-50 border-none rounded-xl px-4 py-4 font-extrabold text-slate-900 focus:ring-2 focus:ring-indigo-600 appearance-none transition-all" required>
                            <option value="cash">💵 Tunai (Cash)</option>
                            <option value="qris">📱 QRIS / E-Wallet</option>
                            <option value="debit">💳 Kartu Debit</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-3 ml-1">Received Amount</label>
                        <div class="relative">
                            <span class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 font-bold">Rp</span>
                            <input type="number" name="jumlah_bayar" class="w-full bg-slate-50 border-none rounded-xl pl-12 pr-5 py-5 font-extrabold text-slate-900 text-xl focus:ring-2 focus:ring-indigo-600 transition-all" placeholder="0" required>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-indigo-600 text-white font-extrabold py-5 rounded-2xl hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-200 flex items-center justify-center gap-2">
                        <i class="bi bi-cash-stack"></i> Complete Order
                    </button>
                </form>
            @endif
        </div>

        <!-- Guest Info -->
        <div class="bg-white p-8 lg:p-10 rounded-[2.5rem] border border-slate-200 shadow-sm">
            <h3 class="text-slate-900 font-extrabold text-xl mb-8">Guest Details</h3>
            <div class="space-y-6">
                <div>
                    <div class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-1">Customer Name</div>
                    <div class="text-slate-900 font-extrabold text-lg leading-none">{{ $order->nama_pelanggan ?? 'Walk-in Guest' }}</div>
                </div>
                <div>
                    <div class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-1">Ordering Time</div>
                    <div class="text-slate-900 font-extrabold text-lg leading-none">{{ $order->created_at->format('H:i') }} WIB</div>
                </div>
                @if($order->catatan)
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                        <div class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-2">Special Note</div>
                        <p class="text-slate-600 text-sm font-medium leading-relaxed italic">"{{ $order->catatan }}"</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
