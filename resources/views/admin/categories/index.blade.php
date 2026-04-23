@extends('layouts.admin')

@section('title', 'Category Management')
@section('page-title', 'Menu Categories')
@section('page-subtitle', 'Organize your dishes and beverages into logical groups.')

@section('content')
<div class="mb-10 flex flex-col sm:flex-row items-center justify-between gap-6">
    <div class="bg-white px-8 py-4 rounded-[2rem] border border-slate-200 shadow-sm flex items-center gap-6">
        <div class="flex flex-col">
            <span class="text-slate-400 text-[10px] font-bold uppercase tracking-widest block mb-1">Active Categories</span>
            <span class="text-slate-900 font-black text-2xl">{{ $categories->count() }}</span>
        </div>
        <div class="w-px h-10 bg-slate-100"></div>
        <div class="flex flex-col">
            <span class="text-slate-400 text-[10px] font-bold uppercase tracking-widest block mb-1">System Status</span>
            <span class="text-emerald-600 font-black text-xs uppercase tracking-widest">Optimized</span>
        </div>
    </div>
    
    <a href="{{ route('admin.categories.create') }}" class="w-full sm:w-auto bg-indigo-600 text-white px-10 py-5 rounded-[2rem] font-extrabold flex items-center justify-center gap-3 hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-100">
        <i class="bi bi-tag-fill text-lg"></i> Create Category
    </a>
</div>

<!-- Bento-style Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
    @foreach($categories as $category)
    <div class="group bg-white rounded-[3rem] border border-slate-200 shadow-sm hover:shadow-2xl hover:border-indigo-100 transition-all duration-500 p-10 relative overflow-hidden flex flex-col h-full">
        <!-- Decoration Pattern -->
        <div class="absolute -top-10 -right-10 w-32 h-32 bg-slate-50 rounded-full group-hover:bg-indigo-50 transition-colors -z-10"></div>
        
        <div class="flex items-center justify-between mb-8">
            <div class="w-16 h-16 bg-slate-50 rounded-[1.5rem] flex items-center justify-center text-slate-400 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-500">
                <i class="bi bi-folder2-open text-3xl"></i>
            </div>
            <div class="text-slate-300 font-black text-4xl opacity-20 group-hover:opacity-40 transition-opacity">0{{ $category->urutan }}</div>
        </div>

        <h3 class="text-slate-900 font-black text-2xl tracking-tight mb-2">{{ $category->nama }}</h3>
        <p class="text-slate-400 text-sm font-medium mb-10 flex-1">This category contains the various menu items related to {{ strtolower($category->nama) }}.</p>
        
        <div class="flex items-center justify-between pt-8 border-t border-slate-50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center font-black text-sm">
                    {{ $category->menus_count ?? 0 }}
                </div>
                <span class="text-slate-400 text-[10px] font-bold uppercase tracking-widest">Linked Menus</span>
            </div>
            
            <div class="flex gap-2">
                <a href="{{ route('admin.categories.edit', $category) }}" class="w-12 h-12 bg-white text-slate-400 border border-slate-200 rounded-xl flex items-center justify-center hover:text-indigo-600 hover:border-indigo-100 transition-all shadow-sm">
                    <i class="bi bi-pencil-square text-lg"></i>
                </a>
                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Delete this category?')" class="inline">
                    @csrf @method('DELETE')
                    <button class="w-12 h-12 bg-white text-slate-400 border border-slate-200 rounded-xl flex items-center justify-center hover:text-rose-600 hover:border-rose-100 transition-all shadow-sm">
                        <i class="bi bi-trash3 text-lg"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection
