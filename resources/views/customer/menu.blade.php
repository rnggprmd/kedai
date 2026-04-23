@extends('layouts.customer')

@section('content')
<div class="relative min-h-screen pb-32">
    <!-- Section Title & Info -->
    <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-8">
        <div class="max-w-2xl">
            <h2 class="text-slate-900 font-black text-4xl lg:text-6xl tracking-tight mb-4 leading-tight">Authentic Taste <br><span class="text-indigo-600">Fresh Ingredients.</span></h2>
            <p class="text-slate-500 font-medium text-lg lg:text-xl">Discover our curated selection of hand-crafted dishes, prepared with passion and served with a smile.</p>
        </div>
        <div class="hidden md:flex flex-col items-end gap-3">
            <div class="flex items-center gap-2 bg-emerald-50 text-emerald-700 px-4 py-2 rounded-2xl border border-emerald-100 shadow-sm">
                <i class="bi bi-clock-fill"></i>
                <span class="font-bold text-sm">Open: 09:00 - 22:00</span>
            </div>
            <div class="flex items-center gap-2 bg-indigo-50 text-indigo-700 px-4 py-2 rounded-2xl border border-indigo-100 shadow-sm">
                <i class="bi bi-geo-alt-fill"></i>
                <span class="font-bold text-sm">Table {{ $table->nama_meja }}</span>
            </div>
        </div>
    </div>

    <!-- Chef's Recommendations -->
    <div class="mb-16">
        <div class="flex items-center gap-3 mb-8">
            <div class="w-12 h-1 bg-indigo-600 rounded-full"></div>
            <h3 class="text-slate-900 font-black text-lg uppercase tracking-widest">Recommended for You</h3>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @php $featured = $categories->first()->menus->take(2); @endphp
            @foreach($featured as $menu)
            <div class="group relative bg-slate-900 rounded-[2.5rem] overflow-hidden h-[280px] flex items-end p-8 lg:p-10 hover:shadow-2xl hover:shadow-indigo-100 transition-all duration-500">
                <img src="{{ $menu->gambar ? Storage::url($menu->gambar) : 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=800' }}" 
                     class="absolute inset-0 w-full h-full object-cover opacity-60 group-hover:scale-110 transition-transform duration-1000">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>
                <div class="relative z-10 w-full flex justify-between items-end gap-6">
                    <div class="flex-1">
                        <span class="px-3 py-1 bg-indigo-600 text-white text-[9px] font-black uppercase tracking-widest rounded-full mb-3 inline-block">Best Seller</span>
                        <h4 class="text-white font-black text-2xl lg:text-3xl mb-1 leading-tight">{{ $menu->nama }}</h4>
                        <p class="text-indigo-400 font-black text-lg">{{ $menu->formatted_harga }}</p>
                    </div>
                    @if($menu->is_available)
                    <button onclick='addToCart(@json($menu))' class="w-14 h-14 bg-white text-slate-900 rounded-2xl flex-shrink-0 flex items-center justify-center hover:bg-indigo-600 hover:text-white transition-all shadow-xl active:scale-90">
                        <i class="bi bi-plus-lg text-2xl"></i>
                    </button>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Sticky Category Navigation & Search -->
    <div class="sticky top-16 z-40 -mx-6 px-6 bg-white border-b border-slate-100 mb-10 py-4 flex flex-col gap-4 shadow-sm">
        <div class="flex items-center gap-3 overflow-x-auto no-scrollbar pb-1">
            @foreach($categories as $category)
            <a href="#category-{{ $category->id }}" class="category-nav-link whitespace-nowrap px-6 py-2.5 rounded-full bg-slate-100 text-slate-500 font-bold text-[10px] uppercase tracking-widest hover:bg-indigo-600 hover:text-white transition-all duration-300">
                {{ $category->nama }}
            </a>
            @endforeach
        </div>
        
        <div class="relative group">
            <i class="bi bi-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
            <input type="text" id="menu-search" placeholder="Search for something delicious..." 
                class="w-full bg-slate-100 border-none rounded-2xl pl-12 pr-5 py-4 text-sm font-bold text-slate-900 focus:ring-2 focus:ring-indigo-600 transition-all placeholder:text-slate-400">
        </div>
    </div>

    <!-- Menu Sections -->
    <div id="menu-container" class="space-y-16">
        @foreach($categories as $category)
        <section id="category-{{ $category->id }}" class="scroll-mt-52 category-section">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-slate-900 font-black text-2xl tracking-tight flex items-center gap-3">
                    <span class="w-2 h-8 bg-indigo-600 rounded-full"></span>
                    {{ $category->nama }}
                </h2>
                <span class="bg-slate-100 text-slate-400 px-4 py-1.5 rounded-full font-bold text-[10px] uppercase tracking-widest">{{ $category->menus->count() }} Items</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                @php
                    $isDrink = str_contains(strtolower($category->nama), 'minum') || str_contains(strtolower($category->nama), 'drink');
                    $fallback = $isDrink 
                        ? 'https://images.unsplash.com/photo-1544145945-f904253d0c7b?w=400' 
                        : 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=400';
                @endphp
                @foreach($category->menus as $menu)
                <div class="menu-item group bg-white rounded-[2.5rem] border border-slate-100 shadow-sm hover:shadow-xl hover:border-indigo-100 transition-all duration-500 flex items-center p-5 gap-6 relative min-h-[140px] {{ !$menu->is_available ? 'opacity-60 grayscale-[0.5]' : '' }}" 
                     data-name="{{ strtolower($menu->nama) }}">
                    
                    <!-- Info (Left) -->
                    <div class="flex-1 min-w-0">
                        <h3 class="text-slate-900 font-black text-lg truncate mb-1 group-hover:text-indigo-600 transition-colors">{{ $menu->nama }}</h3>
                        <p class="text-slate-400 text-[11px] font-medium leading-relaxed line-clamp-2 mb-4">{{ $menu->deskripsi ?? 'No description available for this delicious dish.' }}</p>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-indigo-600 font-black text-lg">{{ $menu->formatted_harga }}</span>
                            
                            @if($menu->is_available)
                            <button onclick='addToCart(@json($menu))' class="w-10 h-10 bg-slate-900 text-white rounded-xl flex items-center justify-center hover:bg-indigo-600 hover:scale-110 transition-all active:scale-90 shadow-lg">
                                <i class="bi bi-plus text-xl"></i>
                            </button>
                            @endif
                        </div>
                    </div>

                    <!-- Image (Right) - Balanced Size -->
                    <div class="relative w-24 h-24 lg:w-28 lg:h-28 flex-shrink-0 rounded-[1.5rem] overflow-hidden bg-slate-50 shadow-inner order-last">
                        <img src="{{ $menu->gambar ? Storage::url($menu->gambar) : $fallback }}" 
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        @if(!$menu->is_available)
                        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-[1px] flex items-center justify-center">
                            <span class="text-white font-black text-[9px] uppercase tracking-widest -rotate-12 border border-white/40 px-2 py-1 rounded">Sold Out</span>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </section>
        @endforeach
    </div>

    <!-- Floating Checkout Pill (Solid & High Contrast) -->
    <div id="cart-pill" class="fixed bottom-8 left-1/2 -translate-x-1/2 z-50 hidden animate-in slide-in-from-bottom-10 duration-500 w-full max-w-md px-6">
        <button onclick="toggleCart()" class="w-full bg-indigo-600 border border-indigo-400 text-white px-8 py-5 rounded-3xl shadow-[0_20px_50px_rgba(79,70,229,0.4)] flex items-center justify-between group hover:scale-[1.02] transition-all active:scale-95" style="background-color: #4f46e5 !important;">
            <div class="flex items-center gap-4">
                <div class="relative">
                    <i class="bi bi-bag-fill text-2xl text-white"></i>
                    <span id="cart-count" class="absolute -top-2 -right-2 w-6 h-6 bg-rose-500 text-white text-[10px] font-black rounded-full flex items-center justify-center border-2 border-indigo-600">0</span>
                </div>
                <div class="text-left">
                    <div class="text-[10px] font-black text-indigo-100 uppercase tracking-widest leading-none mb-1">Total Order</div>
                    <div id="cart-total" class="font-black text-xl text-white leading-none">Rp 0</div>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="h-8 w-px bg-white/20"></div>
                <span class="font-black text-xs text-white uppercase tracking-widest flex items-center gap-2">
                    Review Order <i class="bi bi-chevron-right group-hover:translate-x-1 transition-transform"></i>
                </span>
            </div>
        </button>
    </div>

    <!-- Full-Screen Modern Cart Modal -->
    <div id="cart-modal" class="fixed inset-0 z-[9999] hidden animate-in fade-in zoom-in duration-300">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-slate-900/80 backdrop-blur-sm" onclick="toggleCart()"></div>
        
        <!-- Modal Container -->
        <div class="relative h-full w-full md:max-w-2xl md:h-[90vh] md:mt-[5vh] mx-auto bg-white md:rounded-[3rem] shadow-2xl flex flex-col overflow-hidden">
            <!-- Modal Header (Sticky) -->
            <div class="px-6 py-5 lg:px-10 lg:py-8 border-b border-slate-100 flex items-center justify-between bg-white z-20">
                <div>
                    <h2 class="text-slate-900 font-black text-xl lg:text-2xl tracking-tight">Your Order</h2>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Review your choices</p>
                </div>
                <button onclick="toggleCart()" class="w-10 h-10 lg:w-12 lg:h-12 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-400 hover:text-rose-500 hover:bg-rose-50 transition-all border border-slate-100">
                    <i class="bi bi-x-lg text-lg"></i>
                </button>
            </div>

            <!-- Modal Content (Scrollable Area) -->
            <div class="flex-1 overflow-y-auto custom-scrollbar bg-slate-50/30">
                <!-- Items List -->
                <div id="cart-items" class="px-6 py-8 lg:px-10 space-y-4">
                    <!-- Items injected by JS -->
                </div>
                
                <!-- Order Form Inputs -->
                <div class="px-6 py-8 lg:px-10 border-t border-slate-100 bg-white">
                    <form id="order-form" action="{{ route('customer.order.store', $table->qr_token) }}" method="POST" class="space-y-6">
                        @csrf
                        <div id="hidden-inputs"></div>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-[11px] font-black text-slate-900 uppercase tracking-[0.2em] mb-3 ml-1">Customer Details</label>
                                <input type="text" name="nama_pelanggan" placeholder="Your Name (Optional)" 
                                    class="w-full bg-slate-50 border-slate-200 rounded-2xl px-6 py-4 font-bold text-slate-900 focus:ring-2 focus:ring-indigo-600 focus:bg-white transition-all placeholder:text-slate-400 border-none ring-1 ring-slate-200">
                            </div>
                            
                            <div>
                                <label class="block text-[11px] font-black text-slate-900 uppercase tracking-[0.2em] mb-3 ml-1">Kitchen Notes</label>
                                <textarea name="catatan" placeholder="Any special requests? (Optional)" rows="2"
                                    class="w-full bg-slate-50 border-slate-200 rounded-2xl px-6 py-4 font-bold text-slate-900 focus:ring-2 focus:ring-indigo-600 focus:bg-white transition-all placeholder:text-slate-400 border-none ring-1 ring-slate-200"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                
                <!-- Extra padding for sticky footer -->
                <div class="h-32"></div>
            </div>

            <!-- Modal Footer (Slim Sticky Actions) -->
            <div class="absolute bottom-0 left-0 right-0 p-6 bg-white border-t border-slate-100 z-30 shadow-[0_-10px_40px_rgba(0,0,0,0.03)]">
                <div class="max-w-md mx-auto grid grid-cols-2 gap-3">
                    <button type="button" onclick="toggleCart()" class="order-1 py-4 bg-slate-100 text-slate-500 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-slate-200 transition-all active:scale-95">
                        Back
                    </button>
                    <button type="submit" form="order-form" id="submit-btn" class="order-2 py-4 bg-indigo-600 text-white rounded-2xl font-black text-xs uppercase tracking-[0.15em] shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all active:scale-95 flex items-center justify-center gap-2">
                        <span>Confirm</span>
                        <i class="bi bi-send-fill text-sm"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let cart = [];

    function addToCart(menu) {
        const existing = cart.find(i => i.id === menu.id);
        if (existing) {
            if (existing.qty >= 50) {
                showToast('Maximum 50 items per menu', 'rose');
                return;
            }
            existing.qty++;
        } else {
            cart.push({ ...menu, qty: 1 });
        }
        updateCartUI();
        
        // Show premium feedback
        showToast(`${menu.nama} added to cart!`);
    }

    function toggleCart() {
        const modal = document.getElementById('cart-modal');
        const pill = document.getElementById('cart-pill');
        const isHidden = modal.classList.toggle('hidden');
        
        // Body Scroll Lock & Pill Visibility
        if (!isHidden) {
            document.body.style.overflow = 'hidden'; // Lock background scroll
            pill.style.display = 'none';
        } else {
            document.body.style.overflow = ''; // Unlock background scroll
            if (cart.length > 0) pill.style.display = 'block';
        }
    }

    function updateCartUI() {
        const count = cart.reduce((acc, item) => acc + item.qty, 0);
        const total = cart.reduce((acc, item) => acc + (item.qty * item.harga), 0);
        
        document.getElementById('cart-count').innerText = count;
        document.getElementById('cart-total').innerText = 'Rp ' + total.toLocaleString('id-ID');
        
        const pill = document.getElementById('cart-pill');
        const modal = document.getElementById('cart-modal');
        
        if (count > 0 && modal.classList.contains('hidden')) {
            pill.classList.remove('hidden');
            pill.style.display = 'block';
        } else {
            pill.classList.add('hidden');
            pill.style.display = 'none';
        }
        
        renderCartItems();
        updateHiddenInputs();
    }

    function renderCartItems() {
        const container = document.getElementById('cart-items');
        if (cart.length === 0) {
            container.innerHTML = `<div class="h-full flex flex-col items-center justify-center text-center py-20">
                <i class="bi bi-cart-x text-6xl text-slate-100 mb-4"></i>
                <p class="text-slate-400 font-bold uppercase tracking-widest text-sm">Your cart is empty</p>
            </div>`;
            return;
        }

        container.innerHTML = cart.map((item, index) => `
            <div class="flex items-center gap-6 animate-in slide-in-from-left-10 duration-300" style="animation-delay: ${index * 50}ms">
                <img src="${item.gambar ? '/storage/' + item.gambar : 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=200'}" class="w-20 h-20 rounded-2xl object-cover shadow-sm">
                <div class="flex-1">
                    <h4 class="text-slate-900 font-extrabold text-base mb-1">${item.nama}</h4>
                    <p class="text-indigo-600 font-black text-sm">Rp ${(item.harga * item.qty).toLocaleString('id-ID')}</p>
                </div>
                <div class="flex items-center bg-slate-100 rounded-xl p-1 gap-2 border border-slate-200">
                    <button onclick="changeQty(${item.id}, -1)" class="w-8 h-8 flex items-center justify-center bg-white rounded-lg shadow-sm text-slate-600 hover:text-rose-500 transition-colors">
                        <i class="bi bi-dash-lg"></i>
                    </button>
                    <span class="w-8 text-center font-black text-slate-900">${item.qty}</span>
                    <button onclick="changeQty(${item.id}, 1)" class="w-8 h-8 flex items-center justify-center bg-white rounded-lg shadow-sm text-slate-600 hover:text-indigo-600 transition-colors">
                        <i class="bi bi-plus-lg"></i>
                    </button>
                </div>
            </div>
        `).join('');
    }

    function changeQty(id, delta) {
        const item = cart.find(i => i.id === id);
        if (item) {
            if (delta > 0 && item.qty >= 50) {
                showToast('Maximum 50 items per menu', 'rose');
                return;
            }
            item.qty += delta;
            if (item.qty <= 0) {
                cart = cart.filter(i => i.id !== id);
            }
        }
        updateCartUI();
    }

    function updateHiddenInputs() {
        const container = document.getElementById('hidden-inputs');
        container.innerHTML = cart.map((item, index) => `
            <input type="hidden" name="items[${index}][menu_id]" value="${item.id}">
            <input type="hidden" name="items[${index}][jumlah]" value="${item.qty}">
        `).join('');
    }

    // Handle form submission
    document.getElementById('order-form').addEventListener('submit', function(e) {
        const btn = document.getElementById('submit-btn');
        btn.dataset.loading = 'true';
        btn.disabled = true;
    });

    // Live Search Logic
    document.getElementById('menu-search').addEventListener('input', function(e) {
        const term = e.target.value.toLowerCase();
        const menuItems = document.querySelectorAll('.menu-item');
        const sections = document.querySelectorAll('.category-section');

        menuItems.forEach(item => {
            const name = item.dataset.name;
            if (name.includes(term)) {
                item.classList.remove('hidden');
            } else {
                item.classList.add('hidden');
            }
        });

        // Hide empty sections
        sections.forEach(section => {
            const visibleItems = section.querySelectorAll('.menu-item:not(.hidden)');
            if (visibleItems.length === 0) {
                section.classList.add('hidden');
            } else {
                section.classList.remove('hidden');
            }
        });
    });

    // Scroll-Sync Category Logic
    const observerOptions = {
        root: null,
        rootMargin: '-10% 0px -80% 0px',
        threshold: 0
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const id = entry.target.getAttribute('id');
                const navLinks = document.querySelectorAll('.category-nav-link');
                
                navLinks.forEach(link => {
                    if (link.getAttribute('href') === `#${id}`) {
                        link.classList.remove('bg-slate-100', 'text-slate-600');
                        link.classList.add('bg-indigo-600', 'text-white', 'shadow-lg', 'shadow-indigo-100');
                    } else {
                        link.classList.add('bg-slate-100', 'text-slate-600');
                        link.classList.remove('bg-indigo-600', 'text-white', 'shadow-lg', 'shadow-indigo-100');
                    }
                });
            }
        });
    }, observerOptions);

    document.querySelectorAll('.category-section').forEach(section => {
        observer.observe(section);
    });
</script>
@endpush

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    .scroll-mt-40 { scroll-margin-top: 10rem; }
</style>
@endsection
