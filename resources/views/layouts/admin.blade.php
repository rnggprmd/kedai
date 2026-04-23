@extends('layouts.app')

@section('sidebar-nav')
    <div class="px-3 mb-4">
        <div class="text-slate-500 text-[10px] font-bold uppercase tracking-[0.15em] mb-4">Main Dashboard</div>
        <div class="space-y-1">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                <i class="bi bi-grid-1x2 text-lg"></i>
                <span class="font-bold text-sm">Dashboard</span>
            </a>
            <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.orders.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                <i class="bi bi-cart text-lg"></i>
                <span class="font-bold text-sm">Orders</span>
            </a>
            <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.reports.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                <i class="bi bi-bar-chart text-lg"></i>
                <span class="font-bold text-sm">Reports</span>
            </a>
        </div>
    </div>

    <div class="px-3">
        <div class="text-slate-500 text-[10px] font-bold uppercase tracking-[0.15em] mb-4">Master Data</div>
        <div class="space-y-1">
            <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.categories.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                <i class="bi bi-tag text-lg"></i>
                <span class="font-bold text-sm">Categories</span>
            </a>
            <a href="{{ route('admin.menus.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.menus.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                <i class="bi bi-cup-hot text-lg"></i>
                <span class="font-bold text-sm">Menu Items</span>
            </a>
            <a href="{{ route('admin.tables.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.tables.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                <i class="bi bi-grid-3x3-gap text-lg"></i>
                <span class="font-bold text-sm">Tables</span>
            </a>
            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                <i class="bi bi-people text-lg"></i>
                <span class="font-bold text-sm">Users</span>
            </a>
        </div>
    </div>
@endsection
