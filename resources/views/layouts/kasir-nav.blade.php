<div class="space-y-8">
    <div>
        <div class="text-slate-500 text-[10px] font-bold uppercase tracking-[0.15em] mb-4 px-3">Service Menu</div>
        <ul class="space-y-2">
            <li>
                <a href="{{ route('kasir.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('kasir.dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30 font-bold' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <i class="bi bi-grid-1x2-fill text-lg"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('kasir.orders.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('kasir.orders.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30 font-bold' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <i class="bi bi-reception-4 text-lg"></i>
                    <span>Order Queue</span>
                </a>
            </li>
            <li>
                <a href="{{ route('kasir.menus.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('kasir.menus.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30 font-bold' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <i class="bi bi-journal-text text-lg"></i>
                    <span>Menu Catalog</span>
                </a>
            </li>
        </ul>
    </div>
</div>
