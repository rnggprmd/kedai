@extends('layouts.admin')

@section('title', 'Detail Menu')
@section('page-title', 'Detail Menu')
@section('page-subtitle', 'Informasi lengkap tentang item menu ini.')

@section('topbar-actions')
<div class="flex items-center gap-3">
    <a href="{{ route('admin.menus.edit', $menu) }}"
        class="flex items-center gap-2 px-5 py-2.5 bg-brand-primary text-white rounded-xl font-bold text-sm hover:opacity-90 transition-all shadow-lg shadow-brand-primary/10">
        <i class="bi bi-pencil-square"></i> Edit Menu
    </a>
    <a href="{{ route('admin.menus.index') }}"
        class="flex items-center gap-2 px-5 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl font-bold text-sm hover:bg-slate-50 transition-all">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>
@endsection

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-sm overflow-hidden">
        {{-- Hero Image --}}
        <div class="relative h-72 overflow-hidden bg-slate-100">
            @if($menu->gambar)
                <img src="{{ Storage::url($menu->gambar) }}" class="w-full h-full object-cover" alt="{{ $menu->nama }}">
            @else
                <div class="w-full h-full flex items-center justify-center">
                    <i class="bi bi-cup-hot-fill text-8xl text-slate-200"></i>
                </div>
            @endif
            {{-- Overlay badges --}}
            <div class="absolute top-6 left-6">
                <span class="px-4 py-2 bg-white/90 backdrop-blur-md rounded-full text-[10px] font-black uppercase tracking-widest text-slate-900 shadow-sm">
                    {{ $menu->category->nama }}
                </span>
            </div>
            <div class="absolute top-6 right-6 flex gap-2">
                <span @class([
                    'px-4 py-2 rounded-full text-[10px] font-black uppercase tracking-widest border shadow-sm',
                    'bg-brand-secondary text-white border-brand-secondary' => $menu->is_available,
                    'bg-slate-400 text-white border-slate-300' => !$menu->is_available
                ])>
                    {{ $menu->is_available ? 'Tersedia' : 'Habis' }}
                </span>
                <span @class([
                    'px-4 py-2 rounded-full text-[10px] font-black uppercase tracking-widest border shadow-sm',
                    'bg-white text-brand-secondary border-brand-secondary/20' => $menu->is_active,
                    'bg-white text-slate-400 border-slate-200' => !$menu->is_active
                ])>
                    {{ $menu->is_active ? 'Aktif' : 'Nonaktif' }}
                </span>
            </div>
        </div>

        {{-- Content --}}
        <div class="p-8 lg:p-10">
            <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 mb-8">
                <div>
                    <h2 class="text-slate-900 font-black text-3xl tracking-tight mb-2">{{ $menu->nama }}</h2>
                    @if($menu->deskripsi)
                        <p class="text-slate-500 font-medium text-sm leading-relaxed max-w-xl">{{ $menu->deskripsi }}</p>
                    @else
                        <p class="text-slate-300 font-medium text-sm italic">Tidak ada deskripsi.</p>
                    @endif
                </div>
                <div class="shrink-0 text-right">
                    <div class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-1">Harga</div>
                    <div class="text-brand-primary font-black text-3xl tracking-tight">{{ $menu->formatted_harga }}</div>
                </div>
            </div>

            {{-- Meta Info --}}
            <div class="grid grid-cols-2 gap-4 pt-6 border-t border-slate-100">
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                    <div class="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-1">Kategori</div>
                    <div class="text-slate-900 font-extrabold">{{ $menu->category->nama }}</div>
                </div>
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                    <div class="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-1">Status</div>
                    <div class="text-slate-900 font-extrabold">{{ $menu->is_available ? 'Tersedia' : 'Habis' }} · {{ $menu->is_active ? 'Aktif' : 'Nonaktif' }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
