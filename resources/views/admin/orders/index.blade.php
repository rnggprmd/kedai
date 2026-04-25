@extends('layouts.admin')

@section('title', 'Manajemen Pesanan')
@section('page-title', 'Transaksi')
@section('page-subtitle', 'Pantau dan kelola semua pesanan pelanggan secara real-time.')

@section('content')
<div class="mb-10 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
    {{-- Unified Filter Bar (Pill Style) --}}
    <div class="bg-white p-1.5 rounded-[1.5rem] sm:rounded-full border border-slate-200 shadow-sm flex flex-col sm:flex-row items-stretch sm:items-center gap-2 w-full max-w-3xl transition-all focus-within:ring-4 focus-within:ring-brand-accent/5 focus-within:border-brand-accent">
        {{-- Search Section --}}
        <div class="relative flex-1 group">
            <i class="bi bi-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-brand-accent transition-colors text-sm"></i>
            <input type="text" id="orderSearch" placeholder="Cari Kode, Nama, atau Meja..." 
                class="w-full bg-transparent border-none focus:outline-none focus:ring-0 pl-12 pr-4 py-2.5 text-sm font-bold text-slate-900 placeholder:text-slate-300 outline-none">
        </div>

        {{-- Subtle Divider --}}
        <div class="w-px h-6 bg-slate-100 hidden sm:block"></div>

        {{-- Status Filter Section --}}
        <div class="flex items-center gap-1 px-1 py-1 sm:py-0 overflow-x-auto no-scrollbar">
            <button onclick="filterStatus('all')" data-status-filter="all" class="status-btn flex-none px-6 py-2.5 rounded-full text-[10px] font-black uppercase tracking-widest transition-all bg-brand-accent text-white shadow-lg shadow-brand-accent/20">
                Semua
            </button>
            <button onclick="filterStatus('confirmed')" data-status-filter="confirmed" class="status-btn flex-none px-6 py-2.5 rounded-full text-[10px] font-black uppercase tracking-widest transition-all text-slate-400 hover:text-slate-600 hover:bg-slate-50">
                Menunggu Bayar
            </button>
            <button onclick="filterStatus('completed')" data-status-filter="completed" class="status-btn flex-none px-6 py-2.5 rounded-full text-[10px] font-black uppercase tracking-widest transition-all text-slate-400 hover:text-slate-600 hover:bg-slate-50">
                Selesai
            </button>
            <button onclick="filterStatus('cancelled')" data-status-filter="cancelled" class="status-btn flex-none px-6 py-2.5 rounded-full text-[10px] font-black uppercase tracking-widest transition-all text-slate-400 hover:text-slate-600 hover:bg-slate-50">
                Batal
            </button>
        </div>
    </div>
</div>

<!-- Premium Orders Table -->
<div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse min-w-[1000px]">
            <thead>
                <tr class="bg-slate-50/50">
                    <th class="px-8 py-5 text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] border-b border-slate-100">ID Pesanan</th>
                    <th class="px-8 py-5 text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] border-b border-slate-100">Pelanggan & Meja</th>
                    <th class="px-8 py-5 text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] border-b border-slate-100">Item</th>
                    <th class="px-8 py-5 text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] border-b border-slate-100">Total Nilai</th>
                    <th class="px-8 py-5 text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] border-b border-slate-100">Status</th>
                    <th class="px-8 py-5 text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] border-b border-slate-100">Waktu</th>
                    <th class="px-8 py-5 text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] border-b border-slate-100 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($orders as $order)
                <tr class="order-row hover:bg-slate-50/50 transition-colors group" 
                    data-status="{{ $order->status }}" 
                    data-search="{{ strtolower($order->kode_order . ' ' . $order->nama_pelanggan . ' ' . $order->table->nama_meja) }}">
                    <td class="px-8 py-5">
                        <div class="text-slate-900 font-black text-sm tracking-tight">#{{ $order->kode_order }}</div>
                    </td>
                    <td class="px-8 py-5">
                        <div>
                            <div class="text-slate-900 font-black text-sm tracking-tight leading-tight mb-0.5">{{ $order->nama_pelanggan ?? 'Pelanggan Datang Langsung' }}</div>
                            <div class="text-brand-accent text-[10px] font-bold uppercase tracking-widest">{{ $order->table->nama_meja }}</div>
                        </div>
                    </td>
                    <td class="px-8 py-5">
                        <div class="text-slate-600 text-xs font-bold">{{ $order->items_count }} Item</div>
                    </td>
                    <td class="px-8 py-5">
                        <div class="text-brand-primary font-black text-base">{{ $order->formatted_total }}</div>
                    </td>
                    <td class="px-8 py-5">
                        @php
                            $statusConfig = [
                                'confirmed' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-500', 'label' => 'Menunggu Bayar'],
                                'completed' => ['bg' => 'bg-brand-secondary/10', 'text' => 'text-brand-primary', 'label' => 'Selesai'],
                                'cancelled' => ['bg' => 'bg-red-50', 'text' => 'text-red-500', 'label' => 'Dibatalkan'],
                            ];
                            $cfg = $statusConfig[$order->status] ?? ['bg' => 'bg-slate-50', 'text' => 'text-slate-400', 'label' => $order->status];
                        @endphp
                        <span class="inline-flex items-center gap-1.5 px-4 py-2 {{ $cfg['bg'] }} {{ $cfg['text'] }} rounded-full text-[9px] font-black uppercase tracking-widest border border-current/10">
                            <span class="w-1 h-1 rounded-full bg-current"></span> {{ $cfg['label'] }}
                        </span>
                    </td>
                    <td class="px-8 py-5">
                        <div class="text-slate-400 text-[11px] font-bold uppercase">{{ $order->created_at->format('H:i') }}</div>
                    </td>
                    <td class="px-8 py-5 text-right">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.orders.show', $order) }}" class="w-10 h-10 bg-white border border-slate-200 text-brand-accent rounded-xl flex items-center justify-center hover:bg-brand-accent hover:text-white hover:border-brand-accent transition-all shadow-sm">
                                <i class="bi bi-eye-fill"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
    // Client-side filtering
    const orderSearch = document.getElementById('orderSearch');
    let currentStatusFilter = 'all';

    function applyFilters() {
        const query = orderSearch.value.toLowerCase();
        const rows = document.querySelectorAll('.order-row');

        rows.forEach(row => {
            const searchData = row.dataset.search;
            const status = row.dataset.status;
            
            const matchSearch = searchData.includes(query);
            const matchStatus = currentStatusFilter === 'all' || status === currentStatusFilter;

            row.classList.toggle('hidden', !(matchSearch && matchStatus));
        });
    }

    orderSearch.addEventListener('input', applyFilters);

    window.filterStatus = function(status) {
        currentStatusFilter = status;
        
        document.querySelectorAll('.status-btn').forEach(btn => {
            if (btn.dataset.statusFilter === status) {
                btn.classList.add('bg-brand-accent', 'text-white', 'shadow-lg', 'shadow-brand-accent/20');
                btn.classList.remove('text-slate-400', 'hover:text-slate-600', 'hover:bg-slate-50');
                btn.style.backgroundColor = '#9D4EDD';
                btn.style.color = 'white';
            } else {
                btn.classList.remove('bg-brand-accent', 'text-white', 'shadow-lg', 'shadow-brand-accent/20');
                btn.classList.add('text-slate-400', 'hover:text-slate-600', 'hover:bg-slate-50');
                btn.style.backgroundColor = 'transparent';
                btn.style.color = '';
            }
        });

        applyFilters();
    }
</script>

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endpush
@endsection
