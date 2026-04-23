@extends('layouts.admin')

@section('title', 'Detail Pesanan')
@section('page-title', 'Order Review')
@section('page-subtitle', 'Detailed breakdown of customer transaction.')

@section('content')
<div class="max-w-5xl mx-auto space-y-10">
    <!-- Order Status Header -->
    <div class="bg-white p-8 lg:p-10 rounded-[2rem] border border-slate-200 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex items-center gap-6">
            <div class="w-16 h-16 bg-slate-900 text-white rounded-2xl flex items-center justify-center text-3xl shadow-xl">
                <i class="bi bi-receipt"></i>
            </div>
            <div>
                <h3 class="text-slate-900 font-extrabold text-2xl tracking-tight">Order #{{ $order->kode_order }}</h3>
                <p class="text-slate-400 text-sm font-bold uppercase tracking-widest">{{ $order->created_at->format('d M Y, H:i') }} WIB</p>
            </div>
        </div>
        <div>
            @php
                $statusMap = [
                    'pending' => ['bg-amber-600', 'text-amber-600', 'WAITING CONFIRMATION', 'bi-clock'],
                    'preparing' => ['bg-indigo-600', 'text-indigo-600', 'KITCHEN PREPARING', 'bi-fire'],
                    'ready' => ['bg-sky-600', 'text-sky-600', 'READY FOR PICKUP', 'bi-bell'],
                    'completed' => ['bg-emerald-600', 'text-emerald-600', 'PAID & COMPLETED', 'bi-check-circle'],
                    'cancelled' => ['bg-rose-600', 'text-rose-600', 'CANCELLED', 'bi-x-circle']
                ];
                $s = $statusMap[$order->status] ?? $statusMap['pending'];
            @endphp
            <div class="flex flex-col items-end gap-4">
                <span @class([
                    'inline-flex items-center gap-2 px-6 py-2.5 rounded-full text-[10px] font-black uppercase tracking-[0.2em] border shadow-sm',
                    "bg-white {$s[1]} border-current/10"
                ])>
                    <i class="bi {{ $s[3] }} text-base"></i> {{ $s[2] }}
                </span>
                
                <div class="flex items-center gap-2">
                    @foreach($statusMap as $key => $val)
                        @if($order->status !== $key)
                        <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="{{ $key }}">
                            <button type="submit" @class([
                                "w-10 h-10 rounded-xl flex items-center justify-center transition-all border shadow-sm",
                                "bg-white {$val[1]} border-slate-100 hover:{$val[0]} hover:text-white"
                            ]) title="Set to {{ $val[2] }}">
                                <i class="bi {{ $val[3] }}"></i>
                            </button>
                        </form>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <!-- Main Order Details -->
        <div class="lg:col-span-2 space-y-10">
            <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-8 border-b border-slate-100">
                    <h4 class="text-slate-900 font-extrabold text-lg">Purchased Items</h4>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50">
                                <th class="px-8 py-4 text-slate-400 text-[10px] font-bold uppercase tracking-widest">Item</th>
                                <th class="px-8 py-4 text-slate-400 text-[10px] font-bold uppercase tracking-widest text-center">Qty</th>
                                <th class="px-8 py-4 text-slate-400 text-[10px] font-bold uppercase tracking-widest text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($order->items as $item)
                            <tr>
                                <td class="px-8 py-6">
                                    <div class="text-slate-900 font-extrabold text-base">{{ $item->nama_menu }}</div>
                                    @if($item->catatan)
                                        <div class="text-slate-400 text-xs italic mt-1 font-medium">Note: "{{ $item->catatan }}"</div>
                                    @endif
                                </td>
                                <td class="px-8 py-6 text-center text-slate-900 font-extrabold">{{ $item->jumlah }}</td>
                                <td class="px-8 py-6 text-right text-slate-900 font-extrabold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-slate-900 text-white">
                                <td colspan="2" class="px-8 py-6 font-bold uppercase tracking-widest text-xs opacity-60">Total Payment Amount</td>
                                <td class="px-8 py-6 text-right font-extrabold text-2xl tracking-tighter">{{ $order->formatted_total }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Meta Data Sidebar -->
        <div class="space-y-8">
            <div class="bg-white p-8 rounded-[2rem] border border-slate-200 shadow-sm">
                <h4 class="text-slate-900 font-extrabold text-lg mb-6">Customer Info</h4>
                <div class="space-y-6">
                    <div>
                        <div class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-1">Customer Name</div>
                        <div class="text-slate-900 font-extrabold text-lg leading-tight">{{ $order->nama_pelanggan ?? 'General Guest' }}</div>
                    </div>
                    <div>
                        <div class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-1">Assigned Table</div>
                        <div class="text-slate-900 font-extrabold text-lg leading-tight">Table {{ $order->table->nama_meja }}</div>
                    </div>
                </div>
            </div>

            @if($order->payment)
            <div class="bg-emerald-600 p-8 rounded-[2rem] text-white shadow-xl shadow-emerald-100">
                <h4 class="font-extrabold text-lg mb-6">Payment Record</h4>
                <div class="space-y-6">
                    <div>
                        <div class="text-emerald-200 text-[10px] font-bold uppercase tracking-widest mb-1">Method</div>
                        <div class="font-extrabold text-xl">{{ strtoupper($order->payment->metode) }}</div>
                    </div>
                    <div>
                        <div class="text-emerald-200 text-[10px] font-bold uppercase tracking-widest mb-1">Received Amount</div>
                        <div class="font-extrabold text-xl">Rp {{ number_format($order->payment->jumlah_bayar, 0, ',', '.') }}</div>
                    </div>
                    <div class="pt-4 border-t border-white/20">
                        <div class="text-emerald-200 text-[10px] font-bold uppercase tracking-widest mb-1">Payment Time</div>
                        <div class="font-bold text-sm">{{ $order->payment->created_at->format('H:i:s, d M Y') }}</div>
                    </div>
                </div>
            </div>
            @endif

            <div class="flex flex-col gap-3">
                <a href="{{ route('admin.orders.index') }}" class="w-full bg-slate-100 text-slate-600 font-bold py-4 rounded-2xl text-center hover:bg-slate-200 transition-all">
                    Back to History
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
