@extends('layouts.admin')

@section('title', 'Add Category')
@section('page-title', 'Create Category')
@section('page-subtitle', 'Organize your menus by adding a new category.')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white p-8 lg:p-10 rounded-[2.5rem] border border-slate-200 shadow-sm">
        <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-8">
            @csrf
            <div>
                <label class="block text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-3 ml-1">Category Name</label>
                <input type="text" name="nama" class="w-full bg-slate-50 border-none rounded-2xl px-5 py-4 font-bold text-slate-900 focus:ring-2 focus:ring-indigo-600 transition-all placeholder:text-slate-300" placeholder="e.g. Beverages" value="{{ old('nama') }}" required>
            </div>

            <div class="pt-8 border-t border-slate-50 flex flex-col sm:flex-row gap-4">
                <button type="submit" class="flex-1 bg-indigo-600 text-white font-extrabold py-5 rounded-2xl hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-200">
                    Save Category
                </button>
                <a href="{{ route('admin.categories.index') }}" class="px-10 py-5 bg-slate-100 text-slate-600 font-bold rounded-2xl hover:bg-slate-200 transition-all text-center">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
