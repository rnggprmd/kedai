<div class="space-y-8 px-3">
    <!-- Section: POS Service -->
    <div>
        <div class="text-white/40 text-[10px] font-black uppercase tracking-[0.2em] mb-4 px-1">Layanan POS</div>
        <div class="space-y-1.5">
            <a href="{{ route('kasir.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('kasir.dashboard') ? 'bg-white text-brand-accent shadow-xl font-black' : 'text-white/80 hover:bg-brand-accent/20 hover:text-white' }}">
                <i class="bi bi-grid-1x2-fill text-lg"></i>
                <span class="text-sm">Ringkasan</span>
            </a>
            <a href="{{ route('kasir.orders.create') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('kasir.orders.create') ? 'bg-white text-brand-accent shadow-xl font-black' : 'text-white/80 hover:bg-brand-accent/20 hover:text-white' }}">
                <i class="bi bi-plus-circle-fill text-lg"></i>
                <span class="text-sm">Buat Pesanan</span>
            </a>
        </div>
    </div>

    <!-- Section: Order Management -->
    <div>
        <div class="text-white/40 text-[10px] font-black uppercase tracking-[0.2em] mb-4 px-1">Pelacakan Pesanan</div>
        <div class="space-y-1.5">
            <a href="{{ route('kasir.orders.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('kasir.orders.index') || request()->routeIs('kasir.orders.show') ? 'bg-white text-brand-accent shadow-xl font-black' : 'text-white/80 hover:bg-brand-accent/20 hover:text-white' }}">
                <i class="bi bi-reception-4 text-lg"></i>
                <span class="text-sm">Antrean Pesanan</span>
            </a>
        </div>
    </div>

    <!-- Section: Information -->
    <div>
        <div class="text-white/40 text-[10px] font-black uppercase tracking-[0.2em] mb-4 px-1">Sumber Daya</div>
        <div class="space-y-1.5">
            <a href="{{ route('kasir.menus.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('kasir.menus.*') ? 'bg-white text-brand-accent shadow-xl font-black' : 'text-white/80 hover:bg-brand-accent/20 hover:text-white' }}">
                <i class="bi bi-journal-text text-lg"></i>
                <span class="text-sm">Katalog Menu</span>
            </a>
        </div>
    </div>
</div>
