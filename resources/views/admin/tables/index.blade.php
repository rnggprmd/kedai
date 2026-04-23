@extends('layouts.admin')

@section('title', 'Table Management')
@section('page-title', 'Floor Map')
@section('page-subtitle', 'Manage your restaurant tables, QR codes, and availability.')

@section('content')
<div class="mb-10 flex flex-col sm:flex-row items-center justify-between gap-6">
    <div class="flex items-center gap-4">
        <div class="bg-white px-6 py-3 rounded-2xl border border-slate-200 shadow-sm">
            <span class="text-slate-400 text-[10px] font-bold uppercase tracking-widest block mb-1">Total Tables</span>
            <span class="text-slate-900 font-black text-xl">{{ $tables->count() }}</span>
        </div>
        <div class="bg-white px-6 py-3 rounded-2xl border border-slate-200 shadow-sm">
            <span class="text-slate-400 text-[10px] font-bold uppercase tracking-widest block mb-1">Active Now</span>
            <span class="text-emerald-600 font-black text-xl">{{ $tables->where('is_active', true)->count() }}</span>
        </div>
    </div>
    <a href="{{ route('admin.tables.create') }}" class="w-full sm:w-auto bg-indigo-600 text-white px-8 py-4 rounded-2xl font-extrabold flex items-center justify-center gap-3 hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-100">
        <i class="bi bi-plus-lg text-lg"></i> Add New Table
    </a>
</div>

<!-- Visual Grid Layout -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
    @foreach($tables as $table)
    <div class="group bg-white rounded-[2.5rem] border border-slate-200 shadow-sm hover:shadow-2xl hover:border-indigo-100 transition-all duration-500 overflow-hidden flex flex-col">
        <!-- Card Header -->
        <div class="p-8 pb-4 flex items-start justify-between">
            <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-400 group-hover:bg-indigo-50 group-hover:text-indigo-600 transition-colors">
                <i class="bi bi-door-open text-3xl"></i>
            </div>
            <div @class([
                'px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border',
                'bg-emerald-50 text-emerald-600 border-emerald-100' => $table->is_active,
                'bg-slate-50 text-slate-400 border-slate-100' => !$table->is_active
            ])>
                {{ $table->is_active ? 'Active' : 'Inactive' }}
            </div>
        </div>

        <!-- Card Body -->
        <div class="p-8 pt-0 flex-1">
            <h3 class="text-slate-900 font-black text-2xl tracking-tight mb-1">{{ $table->nama_meja }}</h3>
            <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mb-6">Code: {{ $table->kode_meja }}</p>
            
            <div class="flex items-center gap-6 mb-8">
                <div class="flex flex-col">
                    <span class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-1">Capacity</span>
                    <span class="text-slate-700 font-extrabold text-sm flex items-center gap-2">
                        <i class="bi bi-people-fill text-indigo-500"></i> {{ $table->kapasitas }} Seats
                    </span>
                </div>
                <div class="w-px h-8 bg-slate-100"></div>
                <div class="flex flex-col">
                    <span class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-1">Total Orders</span>
                    <span class="text-slate-700 font-extrabold text-sm flex items-center gap-2">
                        <i class="bi bi-receipt text-amber-500"></i> {{ $table->orders_count ?? 0 }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Card Footer (Actions) -->
        <div class="px-8 py-6 bg-slate-50/50 border-t border-slate-50 flex items-center gap-3">
            <a href="{{ route('admin.tables.show', $table) }}" class="flex-1 bg-white text-slate-900 border border-slate-200 py-3 rounded-xl font-bold text-xs text-center hover:bg-slate-900 hover:text-white hover:border-slate-900 transition-all">
                View QR
            </a>
            <a href="{{ route('admin.tables.edit', $table) }}" class="w-12 h-12 bg-white text-slate-400 border border-slate-200 rounded-xl flex items-center justify-center hover:text-indigo-600 hover:border-indigo-100 transition-all shadow-sm">
                <i class="bi bi-pencil-square"></i>
            </a>
            <form action="{{ route('admin.tables.destroy', $table) }}" method="POST" onsubmit="return confirm('Delete this table?')" class="inline">
                @csrf @method('DELETE')
                <button class="w-12 h-12 bg-white text-slate-400 border border-slate-200 rounded-xl flex items-center justify-center hover:text-rose-600 hover:border-rose-100 transition-all shadow-sm">
                    <i class="bi bi-trash3"></i>
                </button>
            </form>
        </div>
    </div>
    @endforeach
</div>
@endsection
