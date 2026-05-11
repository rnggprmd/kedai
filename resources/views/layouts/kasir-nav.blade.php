<div class="space-y-8 px-3">
    <!-- Group: Main -->
    <div class="mb-6">
        <div class="text-white/20 text-[8px] font-black uppercase tracking-[0.2em] mb-3 px-3">Menu Utama</div>
        <div class="space-y-1">
            <a href="{{ route('kasir.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all {{ request()->routeIs('kasir.dashboard') ? 'bg-white/5 text-white font-bold border-l-2 border-brand-accent' : 'text-white/40 hover:text-white hover:bg-white/5' }}">
                <i class="bi bi-grid-1x2 text-lg"></i>
                <span class="text-sm">Dashboard</span>
            </a>
        </div>
    </div>

    <!-- Group: Point of Sale -->
    <div class="mb-6">
        <div class="text-white/20 text-[8px] font-black uppercase tracking-[0.2em] mb-3 px-3">Layanan Kasir</div>
        <div class="space-y-1">
            <a href="{{ route('kasir.orders.create') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all {{ request()->routeIs('kasir.orders.create') ? 'bg-white/5 text-white font-bold border-l-2 border-brand-accent' : 'text-white/40 hover:text-white hover:bg-white/5' }}">
                <i class="bi bi-plus-circle text-lg"></i>
                <span class="text-sm">Buat Pesanan</span>
            </a>
        </div>
    </div>

    <!-- Group: Management -->
    <div class="mb-6">
        <div class="text-white/20 text-[8px] font-black uppercase tracking-[0.2em] mb-3 px-3">Manajemen Pesanan</div>
        <div class="space-y-1">
            <a href="{{ route('kasir.orders.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all {{ request()->routeIs('kasir.orders.index') || request()->routeIs('kasir.orders.show') ? 'bg-white/5 text-white font-bold border-l-2 border-brand-accent' : 'text-white/40 hover:text-white hover:bg-white/5' }}">
                <i class="bi bi-reception-4 text-lg"></i>
                <span class="text-sm">Antrean Pesanan</span>
            </a>
        </div>
    </div>

    <!-- Group: Reference -->
    <div>
        <div class="text-white/20 text-[8px] font-black uppercase tracking-[0.2em] mb-3 px-3">Referensi Menu</div>
        <div class="space-y-1">
            <a href="{{ route('kasir.menus.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all {{ request()->routeIs('kasir.menus.*') ? 'bg-white/5 text-white font-bold border-l-2 border-brand-accent' : 'text-white/40 hover:text-white hover:bg-white/5' }}">
                <i class="bi bi-journal-text text-lg"></i>
                <span class="text-sm">Katalog Menu</span>
            </a>
        </div>
    </div>
</div>
