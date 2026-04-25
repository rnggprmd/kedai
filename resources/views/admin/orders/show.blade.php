@extends('layouts.admin')

@section('title', 'Detail Pesanan')
@section('page-title', 'Tinjauan Pesanan')
@section('page-subtitle', 'Rincian transaksi dan riwayat pesanan pelanggan.')

@section('topbar-actions')
<div class="flex items-center gap-3">
    @if($order->status == 'completed')
    <a href="{{ route('customer.order.invoice', ['qr_token' => $order->table->qr_token, 'order' => $order->id]) }}" 
        class="bg-white text-slate-400 px-6 py-2.5 rounded-full font-black text-[10px] uppercase tracking-widest hover:text-brand-accent transition-all shadow-sm border border-slate-100 flex items-center gap-2">
        <i class="bi bi-printer-fill"></i> Invoice
    </a>
    @endif
    <a href="{{ route('admin.orders.index') }}" class="bg-white text-slate-400 px-6 py-2.5 rounded-full font-black text-[10px] uppercase tracking-widest hover:text-brand-primary transition-all shadow-sm border border-slate-100 flex items-center gap-2">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch -mt-8">
    
    {{-- ==================== LEFT COLUMN: ORDER DETAILS (8/12) ==================== --}}
    <div class="lg:col-span-8 flex flex-col h-full">
        <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-sm overflow-hidden transition-all duration-500 hover:shadow-xl hover:shadow-slate-200/50 flex flex-col h-full">
            {{-- Header Order --}}
            <div class="p-8 lg:p-10 border-b border-slate-50 bg-slate-50/20 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div class="flex items-center gap-5">
                    <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center text-2xl text-brand-primary shadow-sm border border-slate-100 shrink-0">
                        <i class="bi bi-receipt-cutoff"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-3 mb-1">
                            <h3 class="text-slate-900 font-black text-xl tracking-tight">Order #{{ $order->kode_order }}</h3>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-slate-400 text-[9px] font-black uppercase tracking-widest flex items-center gap-1.5">
                                <i class="bi bi-geo-alt-fill text-brand-primary"></i> {{ $order->table->nama_meja }}
                            </span>
                            <span class="w-1 h-1 bg-slate-200 rounded-full"></span>
                            <p class="text-slate-400 text-[9px] font-black uppercase tracking-widest flex items-center gap-1.5">
                                <i class="bi bi-clock"></i> {{ $order->created_at->format('H:i') }} WIB
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Status Badge --}}
                @php
                    $statusStyles = [
                        'confirmed' => 'bg-amber-500 text-white shadow-amber-500/20',
                        'completed' => 'bg-brand-secondary text-brand-primary shadow-brand-secondary/20',
                        'cancelled' => 'bg-slate-400 text-white shadow-slate-400/20',
                    ];
                    $label = $order->status == 'confirmed' ? 'MENUNGGU BAYAR' : strtoupper($order->status);
                    $style = $statusStyles[$order->status] ?? 'bg-slate-200';
                @endphp
                <div class="px-6 py-2.5 rounded-full {{ $style }} text-[10px] font-black uppercase tracking-widest shadow-lg flex items-center gap-2">
                    <i class="bi {{ $order->status == 'completed' ? 'bi-check-circle-fill' : 'bi-info-circle-fill' }}"></i>
                    {{ $label }}
                </div>
            </div>

            {{-- Table Items --}}
            <div class="overflow-x-auto flex-1">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-10 py-5 text-slate-400 text-[10px] font-black uppercase tracking-widest border-b border-slate-100">Menu</th>
                            <th class="px-10 py-5 text-slate-400 text-[10px] font-black uppercase tracking-widest text-center border-b border-slate-100">Qty</th>
                            <th class="px-10 py-5 text-slate-400 text-[10px] font-black uppercase tracking-widest text-right border-b border-slate-100">Harga</th>
                            <th class="px-10 py-5 text-slate-400 text-[10px] font-black uppercase tracking-widest text-right border-b border-slate-100">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($order->items as $item)
                        <tr class="group hover:bg-slate-50/50 transition-colors">
                            <td class="px-10 py-6">
                                <div class="text-slate-900 font-black text-sm group-hover:text-brand-primary transition-colors">{{ $item->nama_menu }}</div>
                                @if($item->catatan)
                                    <div class="text-slate-400 text-[9px] font-bold italic mt-1 leading-none">"{{ $item->catatan }}"</div>
                                @endif
                            </td>
                            <td class="px-10 py-6 text-center text-slate-900 font-black text-sm">{{ $item->jumlah }}</td>
                            <td class="px-10 py-6 text-right text-slate-400 font-bold text-sm">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                            <td class="px-10 py-6 text-right text-slate-900 font-black">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Combined Footer --}}
            <div class="mt-auto border-t border-slate-100">
                @if($order->catatan)
                <div class="bg-amber-50/20 px-10 py-4 border-b border-amber-100/50 flex items-center gap-3">
                    <i class="bi bi-chat-left-text-fill text-amber-500 text-[10px]"></i>
                    <span class="text-slate-500 text-[10px] font-bold italic">"{{ $order->catatan }}"</span>
                </div>
                @endif
                <div class="bg-brand-primary text-white flex justify-between items-center px-10 py-8">
                    <span class="font-black uppercase tracking-widest text-[11px] opacity-80">Total Pembayaran</span>
                    <span class="font-black text-4xl tracking-tighter leading-none">{{ $order->formatted_total }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ==================== RIGHT COLUMN: INFO & PAYMENT (4/12) ==================== --}}
    <div class="lg:col-span-4 flex flex-col gap-6 sticky top-24 h-full">
        
        {{-- Card: Customer Info (Compact) --}}
        <div class="bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm relative overflow-hidden group shrink-0">
            <h4 class="text-slate-900 font-black text-sm tracking-tight mb-4 flex items-center gap-2">
                <i class="bi bi-person text-brand-primary"></i> Info Pelanggan
            </h4>
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 bg-slate-50/50 rounded-xl border border-slate-100">
                    <span class="text-slate-400 text-[8px] font-black uppercase tracking-widest">Nama</span>
                    <span class="text-slate-900 font-black text-xs truncate max-w-[120px]">{{ $order->nama_pelanggan ?? 'Tamu' }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-slate-50/50 rounded-xl border border-slate-100">
                    <span class="text-slate-400 text-[8px] font-black uppercase tracking-widest">Kasir</span>
                    <span class="text-slate-900 font-black text-xs truncate max-w-[120px]">{{ $order->kasir->name ?? '-' }}</span>
                </div>
            </div>
        </div>

        {{-- Card: Payment Info (Admin Read-Only) --}}
        <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm relative overflow-hidden flex-1 flex flex-col">
            <div class="absolute top-0 left-0 w-full h-1 bg-brand-secondary opacity-20"></div>
            
            <div class="flex-1 flex flex-col justify-center">
                @if($order->payment)
                    <div class="text-center py-4">
                        <div class="w-20 h-20 bg-brand-secondary text-white rounded-full flex items-center justify-center mx-auto mb-6 shadow-2xl shadow-brand-secondary/30">
                            <i class="bi bi-patch-check-fill text-4xl"></i>
                        </div>
                        <h4 class="text-slate-900 font-black text-xl mb-1 uppercase tracking-tight">Lunas</h4>
                        <p class="text-brand-secondary font-black uppercase tracking-widest text-[9px] mb-8">Transaksi Terverifikasi</p>
                        
                        <div class="space-y-3">
                            <div class="flex items-center justify-between px-4 py-2.5 bg-slate-50 rounded-xl border border-slate-100">
                                <span class="text-slate-400 text-[8px] font-black uppercase tracking-widest">Metode</span>
                                <span class="text-slate-900 font-black text-[10px] uppercase">{{ $order->payment->metode }}</span>
                            </div>
                            <div class="flex items-center justify-between px-4 py-2.5 bg-slate-50 rounded-xl border border-slate-100">
                                <span class="text-slate-400 text-[8px] font-black uppercase tracking-widest">Total Bayar</span>
                                <span class="text-brand-primary font-black text-[10px]">Rp {{ number_format($order->payment->jumlah_bayar, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex items-center justify-between px-4 py-2.5 bg-slate-50 rounded-xl border border-slate-100">
                                <span class="text-slate-400 text-[8px] font-black uppercase tracking-widest">Waktu</span>
                                <span class="text-slate-900 font-black text-[10px]">{{ $order->payment->created_at->format('H:i') }} WIB</span>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-10 opacity-60">
                        <div class="w-16 h-16 bg-slate-100 text-slate-300 rounded-full flex items-center justify-center mx-auto mb-6 border border-dashed border-slate-200">
                            <i class="bi bi-wallet2 text-2xl"></i>
                        </div>
                        <h4 class="text-slate-400 font-black text-sm uppercase tracking-tight">Belum Ada Pembayaran</h4>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
