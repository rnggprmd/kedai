@extends('layouts.admin')

@section('title', 'Kelola Menu')
@section('page-title', 'Manajemen Menu')
@section('page-subtitle', 'Kelola katalog makanan dan minuman secara visual.')

@section('content')
<div class="mb-10 flex flex-row items-center justify-between gap-4">
    {{-- Unified Filter Bar (Shrinkable & Scrollable) --}}
    <div class="bg-white p-1.5 rounded-full border border-slate-200 shadow-sm flex flex-row items-center gap-2 flex-1 min-w-0 transition-all focus-within:ring-4 focus-within:ring-brand-accent/5 focus-within:border-brand-accent">
        {{-- Search Section --}}
        <form action="{{ route('admin.menus.index') }}" method="GET" class="relative flex-none w-[150px] sm:w-[220px] group flex items-center">
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
        <div class="flex-1 min-w-0 flex items-center gap-1 px-1 py-1 sm:py-0 overflow-x-auto no-scrollbar">
            {{-- All Categories --}}
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
                    {{ strtoupper($cat->nama) }}
                </a>
            @endforeach
        </div>
    </div>

    {{-- Quick Action Button --}}
    <button onclick="openCreateModal()" class="flex-none whitespace-nowrap bg-brand-primary text-brand-secondary px-8 py-3.5 rounded-full font-black text-[10px] uppercase tracking-widest hover:opacity-90 active:scale-95 transition-all shadow-xl shadow-brand-primary/20 flex items-center justify-center gap-3 shrink-0">
        <i class="bi bi-plus-circle-fill text-lg"></i>
        <span>Tambah Menu</span>
    </button>
</div>

<!-- Visual Menu Grid (Following Kasir Style) -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8" id="menuGrid">
    @foreach($menus as $menu)
    <div class="menu-card group bg-white rounded-[2.5rem] border border-slate-200 shadow-sm hover:shadow-2xl transition-all duration-500 overflow-hidden flex flex-col"
        data-category="{{ $menu->category_id }}"
        data-nama="{{ strtolower($menu->nama) }}">
        
        <!-- Image Header -->
        <div class="relative h-56 overflow-hidden bg-slate-50">
            <img src="{{ $menu->gambar_url }}" 
                 id="img-{{ $menu->id }}"
                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000">
            
            <div class="absolute top-5 left-5">
                <span class="px-4 py-1.5 bg-white/90 backdrop-blur-md rounded-full text-[9px] font-black uppercase tracking-widest text-slate-900 shadow-sm border border-white/20">
                    {{ $menu->category->nama }}
                </span>
            </div>

            <div class="absolute top-5 right-5">
                @if($menu->is_available)
                    <span class="px-3 py-1 bg-brand-secondary text-brand-primary rounded-full text-[9px] font-black uppercase tracking-widest shadow-lg shadow-brand-secondary/20">Tersedia</span>
                @else
                    <span class="px-3 py-1 bg-slate-400 text-white rounded-full text-[9px] font-black uppercase tracking-widest shadow-lg shadow-slate-400/20">Habis</span>
                @endif
            </div>

            {{-- Floating Actions for Admin --}}
            <div class="absolute inset-0 bg-slate-950/40 backdrop-blur-[2px] opacity-0 group-hover:opacity-100 transition-all duration-500 flex items-center justify-center gap-3 translate-y-4 group-hover:translate-y-0">
                <button onclick="openEditModal({{ json_encode([
                    'id' => $menu->id,
                    'nama' => $menu->nama,
                    'category_id' => $menu->category_id,
                    'harga' => $menu->harga,
                    'deskripsi' => $menu->deskripsi,
                    'is_available' => $menu->is_available,
                    'gambar_url' => $menu->gambar_url
                ]) }})" class="w-12 h-12 bg-white text-brand-accent rounded-2xl flex items-center justify-center hover:bg-brand-accent hover:text-white transition-all shadow-xl scale-90 group-hover:scale-100 duration-300">
                    <i class="bi bi-pencil-square text-lg"></i>
                </button>
                <form action="{{ route('admin.menus.destroy', $menu) }}" method="POST" onsubmit="return confirm('Hapus menu ini?')" class="inline">
                    @csrf @method('DELETE')
                    <button class="w-12 h-12 bg-white text-brand-primary rounded-2xl flex items-center justify-center hover:bg-brand-primary hover:text-white transition-all shadow-xl scale-90 group-hover:scale-100 duration-300 delay-75">
                        <i class="bi bi-trash3 text-lg"></i>
                    </button>
                </form>
            </div>
        </div>

        <!-- Content Body -->
        <div class="p-8 flex-1 flex flex-col">
            <h3 class="text-slate-900 font-black text-lg tracking-tight mb-2 truncate">{{ $menu->nama }}</h3>
            <p class="text-slate-400 text-[11px] font-medium leading-relaxed mb-6 line-clamp-2 h-10">{{ $menu->deskripsi ?? 'Hidangan lezat siap disajikan.' }}</p>
            
            <div class="flex items-center justify-between pt-6 border-t border-slate-50 mt-auto">
                <div class="flex flex-col">
                    <span class="text-slate-400 text-[9px] font-bold uppercase tracking-widest mb-1">Harga Standar</span>
                    <span class="text-brand-primary font-black text-xl tracking-tight">{{ $menu->formatted_harga }}</span>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Empty State --}}
<div id="menuEmptyState" class="hidden py-32 text-center">
    <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center text-slate-300 mx-auto mb-6 border border-slate-200">
        <i class="bi bi-search text-4xl"></i>
    </div>
    <h4 class="text-slate-900 font-black text-xl mb-2">Menu Tidak Ditemukan</h4>
    <p class="text-slate-400 font-medium">Coba sesuaikan pencarian atau filter kategori Anda.</p>
</div>

@if($menus->hasPages())
<div class="mt-16">
    {{ $menus->links() }}
</div>
@endif

<!-- Menu Modal (Same as before but aligned styling) -->
<div id="menuModal" class="fixed inset-0 z-[9999] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-md transition-opacity duration-300 opacity-0" id="modalBackdrop" onclick="closeModal()"></div>
    <div class="relative bg-white w-full max-w-[500px] max-h-[90vh] flex flex-col rounded-[2.5rem] shadow-[0_25px_80px_-15px_rgba(0,0,0,0.4)] overflow-hidden transform transition-all duration-300 scale-95 opacity-0" id="modalContent">
        <div class="p-8 border-b border-slate-50 flex items-center justify-between bg-slate-50/50">
            <div>
                <h3 id="modalTitle" class="text-slate-900 font-black text-xl tracking-tight mb-1">Tambah Menu</h3>
                <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest">Data Katalog Produk</p>
            </div>
            <button onclick="closeModal()" class="w-10 h-10 bg-white border border-slate-200 text-slate-400 rounded-xl flex items-center justify-center hover:bg-brand-primary hover:text-white transition-all">
                <i class="bi bi-x-lg text-xs"></i>
            </button>
        </div>
        <div class="flex-1 overflow-y-auto p-8 bg-white">
            <form id="menuForm" action="" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <input type="hidden" id="methodField" name="_method" value="POST">
                
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 flex items-center gap-4">
                    <div class="w-14 h-14 rounded-xl overflow-hidden bg-white border border-slate-200 flex-shrink-0">
                        <img id="preview_image" src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=100" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <div class="text-slate-900 font-black text-sm mb-1" id="preview_nama">Item Baru</div>
                        <div class="text-brand-primary font-black text-xs" id="preview_harga">Rp 0</div>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-slate-400 text-[9px] font-black uppercase tracking-widest mb-2 ml-1">Nama Menu</label>
                        <input type="text" name="nama" id="menu_nama" required oninput="updatePreview()" class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-3 font-bold text-slate-900 text-sm focus:ring-4 focus:ring-brand-accent/5 focus:border-brand-accent outline-none transition-all">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-slate-400 text-[9px] font-black uppercase tracking-widest mb-2 ml-1">Kategori</label>
                            <select name="category_id" id="menu_category_id" required class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-3 font-bold text-slate-900 text-sm appearance-none">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-slate-400 text-[9px] font-black uppercase tracking-widest mb-2 ml-1">Harga (Rp)</label>
                            <input type="number" name="harga" id="menu_harga" required oninput="updatePreview()" class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-3 font-bold text-slate-900 text-sm">
                        </div>
                    </div>
                    <div>
                        <label class="block text-slate-400 text-[9px] font-black uppercase tracking-widest mb-2 ml-1">Deskripsi</label>
                        <textarea name="deskripsi" id="menu_deskripsi" rows="3" class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-3 font-bold text-slate-900 text-sm"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4 items-end">
                        <div>
                            <label class="block text-slate-400 text-[9px] font-black uppercase tracking-widest mb-2 ml-1">Gambar</label>
                            <input type="file" name="gambar" id="menu_gambar" accept="image/*" onchange="previewFile()" class="text-[10px] text-slate-400 file:bg-brand-accent/10 file:text-brand-accent file:border-0 file:rounded-lg file:px-4 file:py-2 file:font-black file:uppercase file:mr-4">
                        </div>
                        <div onclick="toggleAvailability()" class="bg-slate-50 border border-slate-100 rounded-xl px-4 py-2.5 flex items-center justify-between cursor-pointer select-none">
                            <span class="text-slate-900 font-bold text-[10px]" id="status_text">Tersedia</span>
                            <input type="checkbox" name="is_available" id="menu_is_available" value="1" checked class="sr-only peer">
                            <div class="w-8 h-4 bg-slate-200 rounded-full peer peer-checked:bg-brand-secondary relative after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-3 after:w-3 after:transition-all peer-checked:after:translate-x-4"></div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="p-8 border-t border-slate-50 bg-slate-50/50">
            <button type="submit" form="menuForm" class="w-full bg-brand-primary text-brand-secondary font-black py-4 rounded-2xl hover:opacity-90 transition-all shadow-xl shadow-brand-primary/20 text-[10px] uppercase tracking-[0.2em]">
                <i class="bi bi-check2-circle text-base"></i> Simpan Menu
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function updatePreview() {
        const nama = document.getElementById('menu_nama').value;
        const harga = document.getElementById('menu_harga').value;
        document.getElementById('preview_nama').innerText = nama || 'New Item';
        document.getElementById('preview_harga').innerText = harga ? 'Rp ' + parseInt(harga).toLocaleString('id-ID') : 'Rp 0';
    }

    function previewFile() {
        const preview = document.getElementById('preview_image');
        const file = document.getElementById('menu_gambar').files[0];
        const reader = new FileReader();
        reader.onloadend = () => preview.src = reader.result;
        if (file) reader.readAsDataURL(file);
    }

    function openCreateModal() {
        document.getElementById('modalTitle').innerText = 'Tambah Menu';
        document.getElementById('menuForm').action = "{{ route('admin.menus.store') }}";
        document.getElementById('methodField').value = 'POST';
        document.getElementById('menuForm').reset();
        document.getElementById('preview_image').src = 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=100';
        updatePreview();
        showModal();
    }

    function openEditModal(data) {
        document.getElementById('modalTitle').innerText = 'Edit Menu';
        document.getElementById('menuForm').action = "/admin/menus/" + data.id;
        document.getElementById('methodField').value = 'PATCH';
        document.getElementById('menu_nama').value = data.nama;
        document.getElementById('menu_category_id').value = data.category_id;
        document.getElementById('menu_harga').value = data.harga;
        document.getElementById('menu_deskripsi').value = data.deskripsi || '';
        document.getElementById('menu_is_available').checked = data.is_available;
        document.getElementById('status_text').innerText = data.is_available ? 'Tersedia' : 'Habis';
        document.getElementById('preview_image').src = data.gambar_url || 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=100';
        updatePreview();
        showModal();
    }

    function showModal() {
        const modal = document.getElementById('menuModal');
        modal.classList.remove('hidden');
        setTimeout(() => {
            document.getElementById('modalBackdrop').classList.replace('opacity-0', 'opacity-100');
            document.getElementById('modalContent').classList.remove('scale-95', 'opacity-0');
            document.getElementById('modalContent').classList.add('scale-100', 'opacity-100');
        }, 10);
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        document.getElementById('modalBackdrop').classList.replace('opacity-100', 'opacity-0');
        document.getElementById('modalContent').classList.replace('scale-100', 'scale-95');
        document.getElementById('modalContent').classList.replace('opacity-100', 'opacity-0');
        setTimeout(() => {
            document.getElementById('menuModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }, 300);
    }

    function toggleAvailability() {
        const checkbox = document.getElementById('menu_is_available');
        checkbox.checked = !checkbox.checked;
        document.getElementById('status_text').innerText = checkbox.checked ? 'Tersedia' : 'Habis';
    }

</script>

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endpush
@endsection
