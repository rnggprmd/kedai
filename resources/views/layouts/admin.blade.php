@extends('layouts.app')

@push('styles')
<style>
    :root {
        --brand-primary: #1E1E1E;
        --brand-secondary: #FFD60A;
        --brand-accent: #9D4EDD;
    }
</style>
@endpush

@section('sidebar-nav')
    <!-- Group: Main -->
    <div class="px-4 mb-6">
        <div class="text-white/20 text-[8px] font-black uppercase tracking-[0.2em] mb-3 px-3">Menu Utama</div>
        <div class="space-y-1">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-white/5 text-white font-bold border-l-2 border-brand-accent' : 'text-white/40 hover:text-white hover:bg-white/5' }}">
                <i class="bi bi-grid-1x2 text-lg"></i>
                <span class="text-sm">Dashboard</span>
            </a>
        </div>
    </div>

    <!-- Group: Inventory -->
    <div class="px-4 mb-6">
        <div class="text-white/20 text-[8px] font-black uppercase tracking-[0.2em] mb-3 px-3">Manajemen Menu</div>
        <div class="space-y-1">
            <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.categories.*') ? 'bg-white/5 text-white font-bold border-l-2 border-brand-accent' : 'text-white/40 hover:text-white hover:bg-white/5' }}">
                <i class="bi bi-tags text-lg"></i>
                <span class="text-sm">Kategori</span>
            </a>
            <a href="{{ route('admin.menus.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.menus.*') ? 'bg-white/5 text-white font-bold border-l-2 border-brand-accent' : 'text-white/40 hover:text-white hover:bg-white/5' }}">
                <i class="bi bi-journal-text text-lg"></i>
                <span class="text-sm">Katalog Menu</span>
            </a>
        </div>
    </div>

    <!-- Group: Transactions -->
    <div class="px-4 mb-6">
        <div class="text-white/20 text-[8px] font-black uppercase tracking-[0.2em] mb-3 px-3">Operasional POS</div>
        <div class="space-y-1">
            <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.orders.*') ? 'bg-white/5 text-white font-bold border-l-2 border-brand-accent' : 'text-white/40 hover:text-white hover:bg-white/5' }}">
                <i class="bi bi-reception-4 text-lg"></i>
                <span class="text-sm">Antrean Pesanan</span>
            </a>
        </div>
    </div>

    <!-- Group: System -->
    <div class="px-4 mb-6">
        <div class="text-white/20 text-[8px] font-black uppercase tracking-[0.2em] mb-3 px-3">Pengaturan Sistem</div>
        <div class="space-y-1">
            <a href="{{ route('admin.tables.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.tables.*') ? 'bg-white/5 text-white font-bold border-l-2 border-brand-accent' : 'text-white/40 hover:text-white hover:bg-white/5' }}">
                <i class="bi bi-grid-3x3-gap text-lg"></i>
                <span class="text-sm">Meja Lantai</span>
            </a>
            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-white/5 text-white font-bold border-l-2 border-brand-accent' : 'text-white/40 hover:text-white hover:bg-white/5' }}">
                <i class="bi bi-people text-lg"></i>
                <span class="text-sm">Anggota Staf</span>
            </a>
        </div>
    </div>

    <!-- Group: Analytics -->
    <div class="px-4">
        <div class="text-white/20 text-[8px] font-black uppercase tracking-[0.2em] mb-3 px-3">Analitik</div>
        <div class="space-y-1">
            <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.reports.*') ? 'bg-white/5 text-white font-bold border-l-2 border-brand-accent' : 'text-white/40 hover:text-white hover:bg-white/5' }}">
                <i class="bi bi-bar-chart text-lg"></i>
                <span class="text-sm">Laporan Bisnis</span>
            </a>
        </div>
    </div>
@endsection
