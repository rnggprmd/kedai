@extends('layouts.kasir')

@section('title', 'Katalog Menu')
@section('page-title', 'Menu Library')
@section('page-subtitle', 'Check current items, prices, and stock availability.')

@section('content')
<div class="mb-10 flex flex-col lg:flex-row items-center justify-between gap-8">
    <div class="bg-white px-6 py-3 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4 overflow-x-auto no-scrollbar w-full lg:w-auto">
        <span class="text-slate-400 text-[10px] font-black uppercase tracking-widest border-r border-slate-100 pr-4">Quick Filter</span>
        <div class="flex items-center gap-3">
            <a href="{{ route('kasir.menus.index') }}" @class([
                'whitespace-nowrap px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all',
                'bg-slate-900 text-white shadow-lg' => !request('category'),
                'bg-slate-50 text-slate-400 hover:bg-slate-100 hover:text-slate-600' => request('category')
            ])>All Categories</a>
            @foreach($categories as $cat)
                <a href="{{ route('kasir.menus.index', ['category' => $cat->id]) }}" @class([
                    'whitespace-nowrap px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all',
                    'bg-indigo-600 text-white shadow-lg' => request('category') == $cat->id,
                    'bg-slate-50 text-slate-400 hover:bg-slate-100 hover:text-slate-600' => request('category') != $cat->id
                ])>{{ $cat->nama }}</a>
            @endforeach
        </div>
    </div>
</div>

<!-- Premium Product Grid (Read Only for Kasir) -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
    @foreach($menus as $menu)
    <div class="group bg-white rounded-[2.5rem] border border-slate-200 shadow-sm hover:shadow-2xl transition-all duration-500 overflow-hidden flex flex-col">
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
                    'px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border shadow-sm',
                    'bg-emerald-500 text-white border-emerald-400' => $menu->is_available,
                    'bg-rose-500 text-white border-rose-400' => !$menu->is_available
                ])>
                    {{ $menu->is_available ? 'Available' : 'Sold Out' }}
                </div>
            </div>
        </div>

        <!-- Content Body -->
        <div class="p-8 flex-1">
            <h3 class="text-slate-900 font-black text-xl tracking-tight mb-2 truncate">{{ $menu->nama }}</h3>
            <p class="text-slate-400 text-xs font-medium leading-relaxed mb-6 line-clamp-2 h-8">{{ $menu->deskripsi ?? 'Delicious item ready to be served.' }}</p>
            
            <div class="flex items-center justify-between pt-6 border-t border-slate-50 mt-auto">
                <div class="flex flex-col">
                    <span class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-1">Standard Price</span>
                    <span class="text-indigo-600 font-black text-xl tracking-tight">{{ $menu->formatted_harga }}</span>
                </div>
                <a href="{{ route('kasir.menus.show', $menu) }}" class="w-12 h-12 bg-slate-50 text-slate-400 rounded-xl flex items-center justify-center hover:bg-slate-900 hover:text-white transition-all shadow-sm">
                    <i class="bi bi-eye-fill"></i>
                </a>
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

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endsection
