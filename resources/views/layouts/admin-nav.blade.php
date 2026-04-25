    <!-- Section: Overview -->
    <div class="px-3 mb-8">
        <div class="text-white/40 text-[10px] font-black uppercase tracking-[0.2em] mb-4 px-1">Analitik & Dashboard</div>
        <div class="space-y-1.5">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-white text-brand-accent shadow-xl font-black' : 'text-white/80 hover:bg-brand-accent/20 hover:text-white' }}">
                <i class="bi bi-grid-1x2-fill text-lg"></i>
                <span class="text-sm">Ringkasan</span>
            </a>
            <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-200 {{ request()->routeIs('admin.reports.*') ? 'bg-white text-brand-accent shadow-xl font-black' : 'text-white/80 hover:bg-brand-accent/20 hover:text-white' }}">
                <i class="bi bi-bar-chart-fill text-lg"></i>
                <span class="text-sm">Laporan</span>
            </a>
        </div>
    </div>

    <!-- Section: Operations -->
    <div class="px-3 mb-8">
        <div class="text-white/40 text-[10px] font-black uppercase tracking-[0.2em] mb-4 px-1">Operasional</div>
        <div class="space-y-1.5">
            <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-200 {{ request()->routeIs('admin.orders.*') ? 'bg-white text-brand-accent shadow-xl font-black' : 'text-white/80 hover:bg-brand-accent/20 hover:text-white' }}">
                <i class="bi bi-reception-4 text-lg"></i>
                <span class="text-sm">Antrean Pesanan</span>
            </a>
        </div>
    </div>

    <!-- Section: Inventory -->
    <div class="px-3 mb-8">
        <div class="text-white/40 text-[10px] font-black uppercase tracking-[0.2em] mb-4 px-1">Manajemen Inventaris</div>
        <div class="space-y-1.5">
            <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-200 {{ request()->routeIs('admin.categories.*') ? 'bg-white text-brand-accent shadow-xl font-black' : 'text-white/80 hover:bg-brand-accent/20 hover:text-white' }}">
                <i class="bi bi-tags-fill text-lg"></i>
                <span class="text-sm">Kategori</span>
            </a>
            <a href="{{ route('admin.menus.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-200 {{ request()->routeIs('admin.menus.*') ? 'bg-white text-brand-accent shadow-xl font-black' : 'text-white/80 hover:bg-brand-accent/20 hover:text-white' }}">
                <i class="bi bi-cup-hot-fill text-lg"></i>
                <span class="text-sm">Katalog Menu</span>
            </a>
        </div>
    </div>

    <!-- Section: Setup -->
    <div class="px-3">
        <div class="text-white/40 text-[10px] font-black uppercase tracking-[0.2em] mb-4 px-1">Pengaturan Restoran</div>
        <div class="space-y-1.5">
            <a href="{{ route('admin.tables.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-200 {{ request()->routeIs('admin.tables.*') ? 'bg-white text-brand-accent shadow-xl font-black' : 'text-white/80 hover:bg-brand-accent/20 hover:text-white' }}">
                <i class="bi bi-grid-3x3-gap-fill text-lg"></i>
                <span class="text-sm">Meja Lantai</span>
            </a>
            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-white text-brand-accent shadow-xl font-black' : 'text-white/80 hover:bg-brand-accent/20 hover:text-white' }}">
                <i class="bi bi-people-fill text-lg"></i>
                <span class="text-sm">Anggota Staf</span>
            </a>
        </div>
    </div>
