@extends('layouts.admin')

@section('title', 'Add Category')
@section('page-title', 'Tambah Kategori')
@section('page-subtitle', 'Buat kategori baru untuk mengelompokkan menu.')

@section('topbar-actions')
<a href="{{ route('admin.categories.index') }}" class="flex items-center gap-2 px-5 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl font-bold text-sm hover:bg-slate-50 transition-all">
    <i class="bi bi-arrow-left"></i> Kembali
</a>
@endsection

@section('content')
<div class="max-w-2xl">
    <div class="bg-white p-8 lg:p-10 rounded-[2.5rem] border border-slate-200 shadow-sm">

        {{-- Error Summary --}}
        @if($errors->any())
        <div class="bg-brand-primary/5 border border-brand-primary/20 rounded-2xl p-5 mb-8 flex items-start gap-3">
            <i class="bi bi-exclamation-triangle-fill text-brand-primary text-lg mt-0.5"></i>
            <div>
                <div class="font-extrabold text-brand-primary text-sm mb-1">Terdapat kesalahan input</div>
                <ul class="text-brand-primary text-xs font-medium space-y-1">
                    @foreach($errors->all() as $e) <li>• {{ $e }}</li> @endforeach
                </ul>
            </div>
        </div>
        @endif

        <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Nama --}}
            <div>
                <label class="block text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-2.5 ml-1">Nama Kategori <span class="text-brand-primary">*</span></label>
                <input type="text" name="nama"
                    class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 font-bold text-slate-900 focus:ring-2 focus:ring-brand-primary focus:border-brand-primary transition-all placeholder:text-slate-300 @error('nama') border-brand-primary/30 bg-brand-primary/5 @enderror"
                    placeholder="Contoh: Minuman, Makanan Berat..."
                    value="{{ old('nama') }}" required>
                @error('nama') <p class="text-brand-primary text-[10px] font-bold mt-2 ml-1 uppercase tracking-widest">{{ $message }}</p> @enderror
            </div>

            {{-- Urutan --}}
            <div>
                <label class="block text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-2.5 ml-1">Urutan Tampil</label>
                <input type="number" name="urutan" min="0"
                    class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 font-bold text-slate-900 focus:ring-2 focus:ring-brand-primary focus:border-brand-primary transition-all"
                    placeholder="1" value="{{ old('urutan', 0) }}">
                <p class="text-slate-400 text-[10px] font-medium mt-1.5 ml-1">Angka lebih kecil = tampil lebih awal</p>
            </div>

            {{-- Status Aktif --}}
            <div class="bg-slate-50 p-5 rounded-2xl flex items-center justify-between border border-slate-100">
                <div>
                    <div class="text-slate-900 font-extrabold text-sm">Status Aktif</div>
                    <div class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mt-0.5">Kategori akan tampil di menu pelanggan</div>
                </div>
                <div class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" id="is_active" value="1" checked class="sr-only peer">
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand-primary"></div>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-50 flex flex-col sm:flex-row gap-4">
                <button type="submit" class="flex-1 bg-brand-primary text-brand-secondary font-extrabold py-4.5 rounded-2xl hover:opacity-90 active:scale-[0.98] transition-all shadow-xl shadow-brand-primary/20">
                    <i class="bi bi-check2-circle mr-2"></i>Simpan Kategori
                </button>
                <a href="{{ route('admin.categories.index') }}" class="px-8 py-4 bg-slate-100 text-slate-600 font-bold rounded-2xl hover:bg-slate-200 transition-all text-center">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
