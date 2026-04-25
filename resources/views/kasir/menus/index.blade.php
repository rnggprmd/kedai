@extends('layouts.kasir')

@section('title', 'Katalog Menu')
@section('page-title', 'Menu Library')
@section('page-subtitle', 'Daftar makanan dan minuman yang tersedia untuk dipesan.')

@section('content')
<div class="mb-10 flex flex-row items-center justify-between gap-4 -mt-4">
    {{-- Unified Filter Bar (Aligned with Grid) --}}
    <div class="bg-white p-1.5 rounded-full border border-slate-200 shadow-sm flex flex-row items-center gap-2 flex-1 min-w-0 transition-all focus-within:ring-4 focus-within:ring-brand-accent/5 focus-within:border-brand-accent">
        {{-- Search Section --}}
        <form action="{{ route('kasir.menus.index') }}" method="GET" class="relative flex-1 group flex items-center">
            <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-brand-accent transition-colors text-xs"></i>
            @if(request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
            @endif
            @if(request('availability'))
                <input type="hidden" name="availability" value="{{ request('availability') }}">
            @endif
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama menu..." 
                class="w-full bg-transparent border-none focus:outline-none focus:ring-0 pl-10 pr-4 py-2 text-xs font-bold text-slate-900 placeholder:text-slate-300 outline-none">
        </form>

        {{-- Subtle Divider --}}
        <div class="w-px h-6 bg-slate-100 hidden sm:block"></div>

        {{-- Category & Availability Filter Section --}}
        <div class="flex-none flex items-center gap-1 px-1 py-1 sm:py-0 overflow-x-auto no-scrollbar">
            {{-- All Categories --}}
            <a href="{{ request()->fullUrlWithQuery(['category' => null, 'availability' => null]) }}" @class([
                'flex-none px-4 py-2 rounded-full text-[10px] font-black uppercase tracking-widest transition-all',
                'bg-brand-accent text-white shadow-lg shadow-brand-accent/20' => !request('category') && !request('availability'),
                'text-slate-400 hover:text-slate-600 hover:bg-slate-50' => request('category') || request('availability')
            ])>
                Semua
            </a>

            <div class="w-px h-4 bg-slate-100 mx-1 flex-none"></div>

            {{-- Availability Pills --}}
            <a href="{{ request()->fullUrlWithQuery(['availability' => request('availability') === '1' ? null : '1']) }}" @class([
                'flex-none px-4 py-2 rounded-full text-[10px] font-black uppercase tracking-widest transition-all border',
                'bg-brand-secondary text-brand-primary border-brand-secondary shadow-lg shadow-brand-secondary/20' => request('availability') === '1',
                'bg-white text-slate-400 border-slate-100 hover:bg-slate-50' => request('availability') !== '1'
            ])>
                Tersedia
            </a>
            <a href="{{ request()->fullUrlWithQuery(['availability' => request('availability') === '0' ? null : '0']) }}" @class([
                'flex-none px-4 py-2 rounded-full text-[10px] font-black uppercase tracking-widest transition-all border',
                'bg-red-500 text-white border-red-500 shadow-lg shadow-red-500/20' => request('availability') === '0',
                'bg-white text-slate-400 border-slate-100 hover:bg-slate-50' => request('availability') !== '0'
            ])>
                Habis
            </a>

            <div class="w-px h-4 bg-slate-100 mx-1 flex-none"></div>

            @foreach($categories as $cat)
                <a href="{{ request()->fullUrlWithQuery(['category' => request('category') == $cat->id ? null : $cat->id]) }}" @class([
                    'flex-none px-4 py-2 rounded-full text-[10px] font-black uppercase tracking-widest transition-all',
                    'bg-brand-accent text-white shadow-lg shadow-brand-accent/20' => request('category') == $cat->id,
                    'text-slate-400 hover:text-slate-600 hover:bg-slate-50' => request('category') != $cat->id
                ])>
                    {{ $cat->nama }}
                </a>
            @endforeach
        </div>
    </div>
</div>

<!-- Premium Product Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
    @foreach($menus as $menu)
    <div class="group bg-white rounded-[2.5rem] border border-slate-200 shadow-sm hover:shadow-2xl transition-all duration-500 overflow-hidden flex flex-col">
        <!-- Image Header -->
        <div class="relative h-56 overflow-hidden bg-slate-50">
            <img src="{{ $menu->gambar ? Storage::url($menu->gambar) : 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=500' }}" 
                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000">
            <div class="absolute top-5 left-5">
                <span class="px-4 py-1.5 bg-white/90 backdrop-blur-md rounded-full text-[10px] font-black uppercase tracking-widest text-slate-900 shadow-sm">
                    {{ $menu->category->nama }}
                </span>
            </div>
            <div class="absolute top-5 right-5">
                <div @class([
                    'px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest border shadow-sm',
                    'bg-brand-secondary text-white border-brand-secondary shadow-lg shadow-brand-secondary/20' => $menu->is_available,
                    'bg-slate-400 text-white border-slate-300 shadow-lg shadow-slate-400/20' => !$menu->is_available
                ])>
                    {{ $menu->is_available ? 'Available' : 'Sold Out' }}
                </div>
            </div>
        </div>

        <!-- Content Body -->
        <div class="p-8 flex-1 flex flex-col">
            <h3 class="text-slate-900 font-black text-xl tracking-tight mb-2 truncate">{{ $menu->nama }}</h3>
            <p class="text-slate-400 text-xs font-medium leading-relaxed mb-6 line-clamp-2 h-10">{{ $menu->deskripsi ?? 'Hidangan lezat siap disajikan.' }}</p>
            
            <div class="flex items-center justify-between pt-6 border-t border-slate-50 mt-auto">
                <div class="flex flex-col">
                    <span class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-1">Standard Price</span>
                    <span class="text-brand-primary font-black text-xl tracking-tight">{{ $menu->formatted_harga }}</span>
                </div>
                <button onclick="openQuickView({{ json_encode([
                    'nama' => $menu->nama,
                    'category' => $menu->category->nama,
                    'harga' => $menu->formatted_harga,
                    'deskripsi' => $menu->deskripsi,
                    'is_available' => $menu->is_available,
                    'gambar_url' => $menu->gambar ? Storage::url($menu->gambar) : 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=500'
                ]) }})" class="w-12 h-12 bg-white border border-slate-200 text-slate-400 rounded-xl flex items-center justify-center hover:bg-brand-accent hover:text-white hover:border-brand-accent transition-all shadow-sm group/btn">
                    <i class="bi bi-eye-fill"></i>
                </button>
            </div>
        </div>
    </div>
    @endforeach
</div>

@if($menus->hasPages())
<div class="mt-16">
    {{ $menus->links() }}
</div>
@endif

<!-- Quick View Modal (Modern & Clean) -->
<div id="quickViewModal" class="fixed inset-0 z-[9999] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-md transition-opacity duration-300 opacity-0" id="qvBackdrop" onclick="closeQuickView()"></div>
    <div class="relative bg-white w-full max-w-[450px] rounded-[2.5rem] shadow-[0_25px_80px_-15px_rgba(0,0,0,0.4)] overflow-hidden transform transition-all duration-300 scale-95 opacity-0" id="qvContent">
        <div class="relative h-64 sm:h-72">
            <img id="qvImage" src="" class="w-full h-full object-cover">
            <div class="absolute top-6 right-6">
                <button onclick="closeQuickView()" class="w-10 h-10 bg-white/20 backdrop-blur-md border border-white/30 text-white rounded-full flex items-center justify-center hover:bg-white hover:text-slate-900 transition-all shadow-lg">
                    <i class="bi bi-x-lg text-sm"></i>
                </button>
            </div>
            <div class="absolute bottom-0 left-0 right-0 p-8 bg-gradient-to-t from-slate-900/80 to-transparent">
                <span id="qvCategory" class="px-4 py-1.5 bg-brand-accent text-white rounded-full text-[9px] font-black uppercase tracking-widest shadow-lg shadow-brand-accent/20">Category</span>
                <h3 id="qvNama" class="text-white font-black text-2xl tracking-tight mt-3">Menu Name</h3>
            </div>
        </div>
        <div class="p-8 space-y-6">
            <div class="flex items-center justify-between">
                <div class="flex flex-col">
                    <span class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-1">Standard Price</span>
                    <span id="qvHarga" class="text-brand-primary font-black text-2xl tracking-tight">Rp 0</span>
                </div>
                <div id="qvStatus" class="px-4 py-2 rounded-full text-[10px] font-black uppercase tracking-widest border">
                    Available
                </div>
            </div>
            <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100">
                <span class="text-slate-400 text-[10px] font-black uppercase tracking-widest block mb-2">Description</span>
                <p id="qvDeskripsi" class="text-slate-600 text-sm font-medium leading-relaxed italic">
                    No description available.
                </p>
            </div>
            <button onclick="closeQuickView()" class="w-full bg-slate-900 text-white py-4 rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] hover:bg-brand-accent transition-all shadow-xl shadow-slate-200">
                Selesai Melihat
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openQuickView(data) {
        document.getElementById('qvImage').src = data.gambar_url;
        document.getElementById('qvCategory').innerText = data.category;
        document.getElementById('qvNama').innerText = data.nama;
        document.getElementById('qvHarga').innerText = data.harga;
        document.getElementById('qvDeskripsi').innerText = data.deskripsi || 'Nikmati hidangan lezat kami yang disiapkan dengan bahan-bahan berkualitas tinggi.';
        
        const statusDiv = document.getElementById('qvStatus');
        if(data.is_available) {
            statusDiv.innerText = 'AVAILABLE';
            statusDiv.className = 'px-4 py-2 rounded-full text-[10px] font-black uppercase tracking-widest bg-brand-secondary/10 text-brand-secondary border-brand-secondary/20';
        } else {
            statusDiv.innerText = 'SOLD OUT';
            statusDiv.className = 'px-4 py-2 rounded-full text-[10px] font-black uppercase tracking-widest bg-slate-100 text-slate-400 border-slate-200';
        }

        const modal = document.getElementById('quickViewModal');
        modal.classList.remove('hidden');
        setTimeout(() => {
            document.getElementById('qvBackdrop').classList.replace('opacity-0', 'opacity-100');
            document.getElementById('qvContent').classList.remove('scale-95', 'opacity-0');
            document.getElementById('qvContent').classList.add('scale-100', 'opacity-100');
        }, 10);
        document.body.style.overflow = 'hidden';
    }

    function closeQuickView() {
        document.getElementById('qvBackdrop').classList.replace('opacity-100', 'opacity-0');
        document.getElementById('qvContent').classList.replace('scale-100', 'scale-95');
        document.getElementById('qvContent').classList.replace('opacity-100', 'opacity-0');
        setTimeout(() => {
            document.getElementById('quickViewModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }, 300);
    }
</script>

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endpush
@endsection
