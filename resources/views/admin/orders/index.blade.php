@extends('layouts.admin')

@section('title', 'Order Management')
@section('page-title', 'Active Orders')
@section('page-subtitle', 'Monitor and process real-time customer orders from all tables.')

@section('content')
<div class="mb-12 flex flex-col lg:flex-row items-center justify-between gap-8">
    <div class="flex items-center gap-4 overflow-x-auto no-scrollbar w-full lg:w-auto pb-2">
        <a href="{{ route('admin.orders.index') }}" @class([
            'whitespace-nowrap px-8 py-3 rounded-2xl font-bold text-xs uppercase tracking-widest transition-all',
            'bg-slate-900 text-white shadow-lg' => !request('status'),
            'bg-white text-slate-500 border border-slate-100 hover:bg-slate-50' => request('status')
        ])>All Orders</a>
        @foreach(['pending', 'preparing', 'ready', 'completed'] as $st)
            <a href="{{ route('admin.orders.index', ['status' => $st]) }}" @class([
                'whitespace-nowrap px-8 py-3 rounded-2xl font-bold text-xs uppercase tracking-widest transition-all border',
                'bg-indigo-600 text-white border-indigo-600 shadow-lg' => request('status') == $st,
                'bg-white text-slate-500 border-slate-100 hover:bg-slate-50' => request('status') !== $st
            ])>{{ ucfirst($st) }}</a>
        @endforeach
    </div>
</div>

<!-- Order Ticket Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
    @foreach($orders as $order)
    <div class="group bg-white rounded-[2.5rem] border border-slate-200 shadow-sm hover:shadow-2xl transition-all duration-500 overflow-hidden flex flex-col">
        <!-- Ticket Header -->
        <div @class([
            'p-8 flex items-center justify-between border-b',
            'bg-amber-50/50 border-amber-100' => $order->status == 'pending',
            'bg-indigo-50/50 border-indigo-100' => $order->status == 'preparing',
            'bg-emerald-50/50 border-emerald-100' => $order->status == 'ready' || $order->status == 'completed',
            'bg-slate-50 border-slate-100' => $order->status == 'cancelled'
        ])>
            <div>
                <div class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mb-1">Table Access</div>
                <h3 class="text-slate-900 font-black text-2xl tracking-tight">{{ $order->table->nama_meja }}</h3>
            </div>
            <div @class([
                'px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-sm border',
                'bg-white text-amber-600 border-amber-100' => $order->status == 'pending',
                'bg-indigo-600 text-white border-indigo-600' => $order->status == 'preparing',
                'bg-emerald-600 text-white border-emerald-600' => $order->status == 'ready' || $order->status == 'completed',
                'text-slate-400 border-slate-200' => $order->status == 'cancelled'
            ])>
                {{ $order->status_label }}
            </div>
        </div>

        <!-- Ticket Body -->
        <div class="p-8 flex-1">
            <div class="flex justify-between items-start mb-8">
                <div>
                    <div class="text-slate-900 font-extrabold text-base mb-1">{{ $order->nama_pelanggan ?? 'Walk-in Customer' }}</div>
                    <div class="text-slate-400 text-xs font-bold uppercase tracking-widest">{{ $order->kode_order }}</div>
                </div>
                <div class="text-right">
                    <div class="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-1">Order Time</div>
                    <div class="text-slate-700 font-extrabold text-xs">{{ $order->created_at->format('H:i') }}</div>
                </div>
            </div>

            <div class="space-y-4 mb-8">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-slate-500 font-bold">Total Items</span>
                    <span class="text-slate-900 font-black">{{ $order->items_count ?? $order->items->count() }} Items</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-slate-500 font-bold">Total Amount</span>
                    <span class="text-indigo-600 font-black text-lg">{{ $order->formatted_total }}</span>
                </div>
            </div>

            @if($order->catatan)
            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 mb-8">
                <div class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Kitchen Note:</div>
                <p class="text-slate-600 text-xs font-medium italic">"{{ $order->catatan }}"</p>
            </div>
            @endif
        </div>

        <!-- Ticket Actions -->
        <div class="px-8 py-6 bg-slate-50/50 border-t border-slate-50 flex items-center gap-3">
            <a href="{{ route('admin.orders.show', $order) }}" class="flex-1 bg-white text-slate-900 border border-slate-200 py-4 rounded-2xl font-black text-xs uppercase tracking-widest text-center hover:bg-slate-900 hover:text-white transition-all shadow-sm">
                Process Ticket
            </a>
        </div>
    </div>
    @endforeach
</div>

@if($orders->hasPages())
<div class="mt-16">
    {{ $orders->links() }}
</div>
@endif
@endsection
