<div class="space-y-8">
    <div>
        <div class="text-slate-500 text-[10px] font-bold uppercase tracking-[0.15em] mb-4 px-3">Main Dashboard</div>
        <ul class="space-y-2">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30 font-bold' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <i class="bi bi-grid-1x2-fill text-lg"></i>
                    <span>Overview</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('admin.reports.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30 font-bold' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <i class="bi bi-bar-chart-line-fill text-lg"></i>
                    <span>Analytics</span>
                </a>
            </li>
        </ul>
    </div>

    <div>
        <div class="text-slate-500 text-[10px] font-bold uppercase tracking-[0.15em] mb-4 px-3">Inventory & Layout</div>
        <ul class="space-y-2">
            <li>
                <a href="{{ route('admin.menus.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('admin.menus.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30 font-bold' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <i class="bi bi-egg-fried text-lg"></i>
                    <span>Menu Items</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('admin.categories.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30 font-bold' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <i class="bi bi-tag-fill text-lg"></i>
                    <span>Categories</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.tables.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('admin.tables.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30 font-bold' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <i class="bi bi-grid-3x3-gap-fill text-lg"></i>
                    <span>Floor Plan</span>
                </a>
            </li>
        </ul>
    </div>

    <div>
        <div class="text-slate-500 text-[10px] font-bold uppercase tracking-[0.15em] mb-4 px-3">Operational</div>
        <ul class="space-y-2">
            <li>
                <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('admin.orders.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30 font-bold' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <i class="bi bi-receipt-cutoff text-lg"></i>
                    <span>Transactions</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('admin.users.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30 font-bold' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <i class="bi bi-people-fill text-lg"></i>
                    <span>Staff Manager</span>
                </a>
            </li>
        </ul>
    </div>
</div>
