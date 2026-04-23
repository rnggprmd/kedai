@extends('layouts.admin')

@section('title', 'Menu Management')
@section('page-title', 'Menu Items')
@section('page-subtitle', 'Browse and manage your delicious dishes and beverages.')

@section('content')
<div class="mb-10 flex flex-col lg:flex-row items-center justify-between gap-8">
    <!-- Filter & Search (Optional placeholder for future) -->
    <div class="flex items-center gap-4 overflow-x-auto no-scrollbar pb-2 w-full lg:w-auto">
        <a href="{{ route('admin.menus.index') }}" @class([
            'whitespace-nowrap px-6 py-3 rounded-2xl font-bold text-xs uppercase tracking-widest transition-all',
            'bg-slate-900 text-white shadow-lg shadow-slate-200' => !request('category'),
            'bg-white text-slate-500 border border-slate-100 hover:bg-slate-50' => request('category')
        ])>All Items</a>
        @foreach($categories as $category)
            <a href="{{ route('admin.menus.index', ['category' => $category->id]) }}" @class([
                'whitespace-nowrap px-6 py-3 rounded-2xl font-bold text-xs uppercase tracking-widest transition-all',
                'bg-indigo-600 text-white shadow-lg shadow-indigo-100' => request('category') == $category->id,
                'bg-white text-slate-500 border border-slate-100 hover:bg-slate-50' => request('category') != $category->id
            ])>{{ $category->nama }}</a>
        @endforeach
    </div>

    <a href="{{ route('admin.menus.create') }}" class="w-full lg:w-auto bg-indigo-600 text-white px-8 py-4 rounded-2xl font-extrabold flex items-center justify-center gap-3 hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-100">
        <i class="bi bi-plus-lg"></i> Add New Menu
    </a>
</div>

<!-- Premium Product Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
    @foreach($menus as $menu)
    <div class="group bg-white rounded-[2.5rem] border border-slate-200 shadow-sm hover:shadow-2xl hover:border-indigo-100 transition-all duration-500 overflow-hidden flex flex-col">
        <!-- Image Header -->
        <div class="relative h-56 overflow-hidden">
            <img src="{{ $menu->gambar ? Storage::url($menu->gambar) : 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=500' }}" 
                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000">
            <div class="absolute top-5 left-5">
                <span class="px-4 py-1.5 bg-white/90 backdrop-blur-md rounded-full text-[10px] font-black uppercase tracking-widest text-slate-900 shadow-sm">
                    {{ $menu->category->nama }}
                </span>
            </div>
            <div class="absolute top-5 right-5">
                <div @class([
                    'w-3 h-3 rounded-full border-2 border-white shadow-sm',
                    'bg-emerald-500' => $menu->is_available,
                    'bg-rose-500' => !$menu->is_available
                ])></div>
            </div>
        </div>

        <!-- Content Body -->
        <div class="p-8 flex-1">
            <h3 class="text-slate-900 font-black text-xl tracking-tight mb-2 truncate">{{ $menu->nama }}</h3>
            <p class="text-slate-400 text-xs font-medium leading-relaxed mb-6 line-clamp-2 h-8">{{ $menu->deskripsi ?? 'No description provided.' }}</p>
            
            <div class="flex items-center justify-between pt-6 border-t border-slate-50">
                <div class="flex flex-col">
                    <span class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-1">Price</span>
                    <span class="text-indigo-600 font-black text-xl tracking-tight">{{ $menu->formatted_harga }}</span>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.menus.edit', $menu) }}" class="w-10 h-10 bg-slate-50 text-slate-400 rounded-xl flex items-center justify-center hover:bg-indigo-600 hover:text-white transition-all">
                        <i class="bi bi-pencil-square"></i>
                    </a>
                    <form action="{{ route('admin.menus.destroy', $menu) }}" method="POST" onsubmit="return confirm('Delete this menu item?')" class="inline">
                        @csrf @method('DELETE')
                        <button class="w-10 h-10 bg-slate-50 text-slate-400 rounded-xl flex items-center justify-center hover:bg-rose-600 hover:text-white transition-all">
                            <i class="bi bi-trash3"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

@if($menus->hasPages())
<div class="mt-16 bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm">
    {{ $menus->links() }}
</div>
@endif

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endsection
