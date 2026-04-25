@extends('layouts.kasir')

@section('title', 'Buat Pesanan')
@section('page-title', 'Point of Sale')
@section('page-subtitle', 'Input pesanan walk-in pelanggan.')

@section('topbar-actions')
<a href="{{ route('kasir.orders.index') }}" class="bg-white text-slate-400 px-6 py-2.5 rounded-full font-black text-[10px] uppercase tracking-widest hover:text-brand-primary transition-all shadow-sm border border-slate-100 flex items-center gap-2">
    <i class="bi bi-arrow-left"></i> Kembali
</a>
@endsection

@section('content')
<form action="{{ route('kasir.orders.store') }}" method="POST" id="orderForm">
@csrf

{{-- Main Container - High Fidelity POS Layout --}}
<div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start -mt-8">

    {{-- ==================== SISI KIRI (2/3): PILIH MENU ==================== --}}
    <div class="md:col-span-7 lg:col-span-8 flex flex-col gap-6">
        
        {{-- Pill-Style Filter & Search Bar (Matched with Admin Style) --}}
        <div class="bg-white p-1.5 rounded-[1.5rem] sm:rounded-full border border-slate-200 shadow-sm flex flex-col sm:flex-row items-stretch sm:items-center gap-2 w-full transition-all focus-within:ring-4 focus-within:ring-brand-accent/5 focus-within:border-brand-accent">
            {{-- Search Section (Wider) --}}
            <div class="relative flex-[2] min-w-[200px] group">
                <i class="bi bi-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-brand-accent transition-colors text-sm"></i>
                <input type="text" id="menuSearch" placeholder="Ketik untuk cari menu..." 
                    class="w-full bg-transparent border-none focus:outline-none focus:ring-0 pl-12 pr-4 py-3 text-sm font-black text-slate-900 placeholder:text-slate-300 outline-none">
            </div>

            {{-- Subtle Divider --}}
            <div class="w-px h-6 bg-slate-100 hidden sm:block"></div>

            {{-- Category Filter Section (Horizontal Scrollable) --}}
            <div class="flex flex-1 items-center gap-1 px-1 py-1 sm:py-0 overflow-x-auto no-scrollbar">
                <button type="button" onclick="filterCategory('all')" data-category="all" class="category-btn flex-none px-6 py-2.5 rounded-full text-[10px] font-black uppercase tracking-widest transition-all bg-brand-accent text-white shadow-lg shadow-brand-accent/20">
                    Semua
                </button>
                @foreach($categories as $cat)
                <button type="button" onclick="filterCategory('{{ $cat->id }}')" data-category="{{ $cat->id }}" class="category-btn flex-none px-6 py-2.5 rounded-full text-[10px] font-black uppercase tracking-widest transition-all text-slate-400 hover:text-slate-600 hover:bg-slate-50">
                    {{ strtoupper($cat->nama) }}
                </button>
                @endforeach
            </div>
        </div>

        {{-- Grid Menu (Responsive columns) --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6 pb-20" id="menuGrid">
            @foreach($menus as $menu)
            <div class="menu-card group relative bg-white rounded-[2rem] border border-slate-100 shadow-sm hover:border-brand-primary/20 hover:shadow-xl hover:shadow-brand-primary/5 transition-all duration-300 overflow-hidden cursor-pointer active:scale-95"
                data-menu-id="{{ $menu->id }}"
                data-category="{{ $menu->category_id }}"
                data-name="{{ strtolower($menu->nama) }}"
                data-real-name="{{ $menu->nama }}"
                data-price="{{ $menu->harga }}"
                onclick="addToCart(this)">
                
                <div class="aspect-square bg-slate-50 relative overflow-hidden">
                    @if($menu->gambar)
                        <img src="{{ asset('storage/' . $menu->gambar) }}" alt="{{ $menu->nama }}"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-slate-200">
                            <i class="bi bi-cup-hot-fill text-4xl"></i>
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-brand-primary/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                </div>

                <div class="p-4 text-center">
                    <div class="text-slate-900 font-black text-[13px] tracking-tight truncate mb-1 group-hover:text-brand-primary transition-colors">{{ $menu->nama }}</div>
                    <div class="text-brand-primary font-black text-sm">Rp {{ number_format($menu->harga, 0, ',', '.') }}</div>
                </div>

                {{-- Qty Badge --}}
                <div id="qty-badge-{{ $menu->id }}" class="absolute top-3 right-3 bg-brand-primary text-white w-6 h-6 rounded-full flex items-center justify-center text-[9px] font-black shadow-lg scale-0 transition-transform border-2 border-white">0</div>
            </div>
            @endforeach
        </div>

        {{-- Empty State --}}
        <div id="menuEmptyState" class="hidden py-32 text-center">
            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center text-slate-200 mx-auto mb-4 border border-slate-100">
                <i class="bi bi-search text-2xl"></i>
            </div>
            <p class="text-slate-400 font-black text-[10px] uppercase tracking-widest">Menu tidak ditemukan</p>
        </div>
    </div>

    {{-- ==================== SISI KANAN (1/3): SIDEBAR DATA & CART ==================== --}}
    <div class="md:col-span-5 lg:col-span-4 flex flex-col gap-6 sticky top-24">
        
        <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-sm overflow-hidden flex flex-col h-auto">
            {{-- Bagian 1: Data Pelanggan --}}
            <div class="p-6 border-b border-slate-50 bg-slate-50/20 shrink-0">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-8 h-8 bg-brand-primary/10 text-brand-primary rounded-lg flex items-center justify-center">
                        <i class="bi bi-person-fill text-sm"></i>
                    </div>
                    <h3 class="text-slate-900 font-black text-base tracking-tight">Data Pelanggan</h3>
                </div>
                
                <div class="space-y-3">
                    <div class="relative">
                        <select name="table_id" id="table_id" required
                            class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 font-bold text-slate-900 focus:ring-4 focus:ring-brand-primary/5 focus:border-brand-primary appearance-none transition-all text-xs outline-none">
                            <option value="">— PILIH MEJA —</option>
                            @foreach($tables as $table)
                                <option value="{{ $table->id }}">{{ $table->nama_meja }} ({{ $table->kapasitas }} Kursi)</option>
                            @endforeach
                        </select>
                        <i class="bi bi-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-xs"></i>
                    </div>
                    <input type="text" name="nama_pelanggan" placeholder="Nama Pelanggan (Walk-in)"
                        class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 font-bold text-slate-900 placeholder-slate-300 focus:ring-4 focus:ring-brand-primary/5 focus:border-brand-primary transition-all text-xs outline-none">
                    <textarea name="catatan" rows="2" placeholder="Catatan ke dapur..."
                        class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 font-bold text-slate-900 placeholder-slate-300 focus:ring-4 focus:ring-brand-primary/5 focus:border-brand-primary transition-all resize-none text-xs outline-none"></textarea>
                </div>
            </div>

            {{-- Bagian 2: Daftar Item --}}
            <div class="flex flex-col">
                <div class="px-6 py-4 flex items-center justify-between shrink-0">
                    <h3 class="text-slate-900 font-black text-sm flex items-center gap-2">
                        <i class="bi bi-cart-fill text-brand-secondary"></i> Item Pesanan
                    </h3>
                    <span id="cartCount" class="text-slate-400 text-[9px] font-black uppercase tracking-widest">0 Item</span>
                </div>

                <div id="cartItems" class="max-h-[265px] overflow-y-auto no-scrollbar divide-y divide-slate-50 px-2">
                    {{-- Item JS --}}
                </div>

                {{-- Empty State --}}
                <div id="cartEmpty" class="py-8 text-center flex-1 flex flex-col items-center justify-center">
                    <div class="w-10 h-10 bg-slate-50 rounded-full flex items-center justify-center text-slate-200 mx-auto mb-3 border border-slate-100">
                        <i class="bi bi-cart text-lg"></i>
                    </div>
                    <p class="text-slate-400 text-[9px] font-black uppercase tracking-widest">Keranjang Kosong</p>
                </div>
            </div>

            {{-- Bagian 3: Summary & Simpan (Fixed at Bottom) --}}
            <div class="p-6 bg-slate-50 border-t border-slate-100 space-y-4 shrink-0">
                <div class="flex justify-between items-end">
                    <span class="text-slate-400 font-black text-[9px] uppercase tracking-widest">Total Bayar</span>
                    <span id="cartTotal" class="text-brand-primary font-black text-2xl tracking-tighter">Rp 0</span>
                </div>

                <div id="cartInputs"></div>

                <button type="submit" id="submitBtn" disabled
                    class="w-full bg-brand-primary text-brand-secondary font-black py-3 rounded-xl hover:opacity-90 active:scale-95 transition-all shadow-lg shadow-brand-primary/20 disabled:opacity-30 disabled:cursor-not-allowed disabled:shadow-none disabled:active:scale-100 flex items-center justify-center gap-2 text-[11px] uppercase tracking-widest">
                    <i class="bi bi-check2-circle text-base"></i>
                    Simpan Pesanan
                </button>
            </div>
        </div>
    </div>

</div>
</form>

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>

@push('scripts')
<script>
let cart = {};

function formatRp(num) {
    return 'Rp ' + num.toLocaleString('id-ID');
}

function addToCart(element) {
    const id = element.dataset.menuId;
    const name = element.dataset.realName;
    const harga = parseFloat(element.dataset.price);

    if (cart[id]) {
        cart[id].jumlah++;
    } else {
        cart[id] = { name, harga, jumlah: 1, catatan: '' };
    }
    renderCart();
    updateBadge(id);
}

function updateBadge(id) {
    const badge = document.getElementById(`qty-badge-${id}`);
    if (!badge) return;
    const qty = cart[id] ? cart[id].jumlah : 0;
    if (qty > 0) {
        badge.textContent = qty;
        badge.classList.remove('scale-0');
        badge.classList.add('scale-100');
    } else {
        badge.classList.remove('scale-100');
        badge.classList.add('scale-0');
    }
}

function updateQty(id, delta) {
    if (!cart[id]) return;
    cart[id].jumlah += delta;
    if (cart[id].jumlah <= 0) {
        delete cart[id];
    }
    renderCart();
    updateBadge(id);
}

function removeItem(id) {
    delete cart[id];
    renderCart();
    updateBadge(id);
}

function updateCatatan(id, val) {
    if (cart[id]) cart[id].catatan = val;
    rebuildHiddenInputs();
}

function renderCart() {
    const keys = Object.keys(cart);
    const cartItemsEl = document.getElementById('cartItems');
    const cartEmptyEl = document.getElementById('cartEmpty');
    const cartCountEl = document.getElementById('cartCount');
    const submitBtn   = document.getElementById('submitBtn');

    if (keys.length === 0) {
        cartItemsEl.innerHTML = '';
        cartEmptyEl.classList.remove('hidden');
        cartCountEl.textContent = '0 ITEM';
        document.getElementById('cartTotal').textContent = 'Rp 0';
        document.getElementById('cartInputs').innerHTML = '';
        submitBtn.disabled = true;
        return;
    }

    cartEmptyEl.classList.add('hidden');
    submitBtn.disabled = false;

    let html = '';
    let total = 0;
    let itemCount = 0;

    keys.forEach(id => {
        const item = cart[id];
        const subtotal = item.harga * item.jumlah;
        total += subtotal;
        itemCount += item.jumlah;

        html += `
        <div class="p-4 hover:bg-slate-50/50 transition-colors group">
            <div class="flex items-start justify-between gap-3 mb-2">
                <div class="flex-1 min-w-0">
                    <div class="text-slate-900 font-black text-xs tracking-tight truncate group-hover:text-brand-primary transition-colors">${item.name}</div>
                    <div class="text-slate-400 font-bold text-[9px] uppercase tracking-widest">${formatRp(item.harga)}</div>
                </div>
                <div class="text-brand-primary font-black text-xs tracking-tight">${formatRp(subtotal)}</div>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3 bg-white border border-slate-100 rounded-xl p-1 shadow-sm">
                    <button type="button" onclick="updateQty('${id}', -1)" class="w-8 h-8 rounded-lg bg-slate-50 text-slate-400 hover:bg-brand-primary hover:text-white transition-all flex items-center justify-center font-black"><i class="bi bi-dash"></i></button>
                    <span class="text-slate-900 font-black text-sm w-6 text-center">${item.jumlah}</span>
                    <button type="button" onclick="updateQty('${id}', 1)" class="w-8 h-8 rounded-lg bg-slate-50 text-slate-400 hover:bg-brand-secondary hover:text-brand-primary transition-all flex items-center justify-center font-black"><i class="bi bi-plus"></i></button>
                </div>
                <button type="button" onclick="removeItem('${id}')" class="text-slate-300 hover:text-brand-primary transition-colors p-2">
                    <i class="bi bi-trash3 text-sm"></i>
                </button>
            </div>
        </div>`;
    });

    cartItemsEl.innerHTML = html;
    cartCountEl.textContent = itemCount + ' ITEM';
    document.getElementById('cartTotal').textContent = formatRp(total);
    rebuildHiddenInputs();
}

function rebuildHiddenInputs() {
    const keys = Object.keys(cart);
    let html = '';
    keys.forEach((id, index) => {
        const item = cart[id];
        html += `<input type="hidden" name="items[${index}][menu_id]" value="${id}">`;
        html += `<input type="hidden" name="items[${index}][jumlah]" value="${item.jumlah}">`;
        html += `<input type="hidden" name="items[${index}][catatan]" value="${item.catatan}">`;
    });
    document.getElementById('cartInputs').innerHTML = html;
}

function filterCategory(categoryId) {
    document.querySelectorAll('.category-btn').forEach(btn => {
        if (btn.dataset.category == categoryId) {
            btn.classList.add('bg-brand-accent', 'text-white', 'shadow-lg', 'shadow-brand-accent/20');
            btn.classList.remove('text-slate-400', 'hover:text-slate-600', 'hover:bg-slate-50');
            btn.style.backgroundColor = 'var(--brand-accent)';
            btn.style.color = 'white';
        } else {
            btn.classList.remove('bg-brand-accent', 'text-white', 'shadow-lg', 'shadow-brand-accent/20');
            btn.classList.add('text-slate-400', 'hover:text-slate-600', 'hover:bg-slate-50');
            btn.style.backgroundColor = 'transparent';
            btn.style.color = '';
        }
    });
    applyFilters(categoryId, document.getElementById('menuSearch').value.toLowerCase());
}

document.getElementById('menuSearch').addEventListener('input', function() {
    const activeBtn = document.querySelector('.category-btn.bg-brand-accent');
    const activeCat = activeBtn ? activeBtn.dataset.category : 'all';
    applyFilters(activeCat, this.value.toLowerCase());
});

function applyFilters(categoryId, searchVal) {
    let visibleCount = 0;
    document.querySelectorAll('.menu-card').forEach(card => {
        const matchCat  = categoryId === 'all' || card.dataset.category == categoryId;
        const matchName = card.dataset.name.includes(searchVal);
        const visible   = matchCat && matchName;
        card.classList.toggle('hidden', !visible);
        if (visible) visibleCount++;
    });
    document.getElementById('menuEmptyState').classList.toggle('hidden', visibleCount > 0);
}

document.getElementById('orderForm').addEventListener('submit', function(e) {
    if (!document.getElementById('table_id').value) {
        e.preventDefault();
        alert('Silakan pilih meja!');
        return;
    }
    if (Object.keys(cart).length === 0) {
        e.preventDefault();
        alert('Keranjang kosong!');
        return;
    }
});
</script>
@endpush
@endsection
