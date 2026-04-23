@extends('layouts.admin')

@section('title', 'Add New Table')
@section('page-title', 'Create Table')
@section('page-subtitle', 'Add a new physical table to your restaurant layout.')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white p-8 lg:p-10 rounded-[2.5rem] border border-slate-200 shadow-sm">
        <form action="{{ route('admin.tables.store') }}" method="POST" class="space-y-8">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Code -->
                <div>
                    <label class="block text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-3 ml-1">Unique Table Code</label>
                    <input type="text" name="kode_meja" class="w-full bg-slate-50 border-none rounded-2xl px-5 py-4 font-bold text-slate-900 focus:ring-2 focus:ring-indigo-600 transition-all placeholder:text-slate-300 @error('kode_meja') ring-2 ring-rose-500 @enderror" placeholder="e.g. T-01" value="{{ old('kode_meja') }}" required>
                    @error('kode_meja') <p class="text-rose-600 text-[10px] font-bold mt-2 ml-1 uppercase tracking-widest">{{ $message }}</p> @enderror
                </div>

                <!-- Name -->
                <div>
                    <label class="block text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-3 ml-1">Display Name</label>
                    <input type="text" name="nama_meja" class="w-full bg-slate-50 border-none rounded-2xl px-5 py-4 font-bold text-slate-900 focus:ring-2 focus:ring-indigo-600 transition-all placeholder:text-slate-300 @error('nama_meja') ring-2 ring-rose-500 @enderror" placeholder="e.g. VIP Area 1" value="{{ old('nama_meja') }}" required>
                    @error('nama_meja') <p class="text-rose-600 text-[10px] font-bold mt-2 ml-1 uppercase tracking-widest">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Capacity -->
                <div>
                    <label class="block text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-3 ml-1">Seating Capacity</label>
                    <input type="number" name="kapasitas" class="w-full bg-slate-50 border-none rounded-2xl px-5 py-4 font-bold text-slate-900 focus:ring-2 focus:ring-indigo-600 transition-all" value="{{ old('kapasitas', 2) }}" min="1" required>
                </div>

                <!-- Active Status -->
                <div class="bg-slate-50 p-5 rounded-2xl flex items-center justify-between border border-slate-100">
                    <div class="text-slate-900 font-extrabold text-xs uppercase tracking-tight">Active Status</div>
                    <div class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" id="is_active" value="1" checked class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                    </div>
                </div>
            </div>

            <div class="pt-8 border-t border-slate-50 flex flex-col sm:flex-row gap-4">
                <button type="submit" class="flex-1 bg-indigo-600 text-white font-extrabold py-5 rounded-2xl hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-200">
                    Save Table
                </button>
                <a href="{{ route('admin.tables.index') }}" class="px-10 py-5 bg-slate-100 text-slate-600 font-bold rounded-2xl hover:bg-slate-200 transition-all text-center">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
