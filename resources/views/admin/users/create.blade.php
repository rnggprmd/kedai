@extends('layouts.admin')

@section('title', 'Tambah Staff')
@section('page-title', 'Tambah Pengguna')
@section('page-subtitle', 'Buat akun baru untuk admin atau kasir.')

@section('topbar-actions')
<a href="{{ route('admin.users.index') }}" class="flex items-center gap-2 px-5 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl font-bold text-sm hover:bg-slate-50 transition-all">
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
                <ul class="text-brand-primary/70 text-xs font-medium space-y-1">
                    @foreach($errors->all() as $e) <li>• {{ $e }}</li> @endforeach
                </ul>
            </div>
        </div>
        @endif

        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Full Name --}}
            <div>
                <label class="block text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-2.5 ml-1">Nama Lengkap <span class="text-brand-primary">*</span></label>
                <input type="text" name="name"
                    class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 font-bold text-slate-900 focus:ring-2 focus:ring-brand-primary focus:border-brand-primary transition-all placeholder:text-slate-300 @error('name') border-brand-primary/30 bg-brand-primary/5 @enderror"
                    placeholder="Contoh: Budi Santoso"
                    value="{{ old('name') }}" required>
                @error('name') <p class="text-brand-primary text-[10px] font-bold mt-2 ml-1 uppercase tracking-widest">{{ $message }}</p> @enderror
            </div>

            {{-- Email --}}
            <div>
                <label class="block text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-2.5 ml-1">Alamat Email <span class="text-brand-primary">*</span></label>
                <div class="relative">
                    <i class="bi bi-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 text-sm"></i>
                    <input type="email" name="email"
                        class="w-full bg-slate-50 border border-slate-200 rounded-2xl pl-11 pr-5 py-4 font-bold text-slate-900 focus:ring-2 focus:ring-brand-primary focus:border-brand-primary transition-all placeholder:text-slate-300 @error('email') border-brand-primary/30 bg-brand-primary/5 @enderror"
                        placeholder="budi@kedaipos.com"
                        value="{{ old('email') }}" required>
                </div>
                @error('email') <p class="text-brand-primary text-[10px] font-bold mt-2 ml-1 uppercase tracking-widest">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Role --}}
                <div>
                    <label class="block text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-2.5 ml-1">Role Akses <span class="text-brand-primary">*</span></label>
                    <div class="relative">
                        <i class="bi bi-shield-check absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 text-sm"></i>
                        <select name="role"
                            class="w-full bg-slate-50 border border-slate-200 rounded-2xl pl-11 pr-5 py-4 font-bold text-slate-900 focus:ring-2 focus:ring-brand-primary focus:border-brand-primary appearance-none transition-all" required>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                            <option value="kasir" {{ old('role') == 'kasir' ? 'selected' : '' }}>Staff Kasir</option>
                        </select>
                        <i class="bi bi-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 text-xs pointer-events-none"></i>
                    </div>
                </div>

                {{-- Password --}}
                <div>
                    <label class="block text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-2.5 ml-1">Password <span class="text-brand-primary">*</span></label>
                    <div class="relative">
                        <i class="bi bi-key absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 text-sm"></i>
                        <input type="password" name="password" id="passwordField"
                            class="w-full bg-slate-50 border border-slate-200 rounded-2xl pl-11 pr-11 py-4 font-bold text-slate-900 focus:ring-2 focus:ring-brand-primary focus:border-brand-primary transition-all placeholder:text-slate-300 @error('password') border-brand-primary/30 bg-brand-primary/5 @enderror"
                            placeholder="Min. 8 karakter" required>
                        <button type="button" onclick="togglePwd('passwordField','eyeIconNew')" tabindex="-1"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 hover:text-slate-600 transition-colors">
                            <i class="bi bi-eye" id="eyeIconNew"></i>
                        </button>
                    </div>
                    @error('password') <p class="text-brand-primary text-[10px] font-bold mt-2 ml-1 uppercase tracking-widest">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="pt-6 border-t border-slate-50 flex flex-col sm:flex-row gap-4">
                <button type="submit" class="flex-1 bg-brand-primary text-white font-extrabold py-4.5 rounded-2xl hover:opacity-90 active:scale-[0.98] transition-all shadow-xl shadow-brand-primary/10">
                    <i class="bi bi-person-plus-fill mr-2"></i>Buat Akun
                </button>
                <a href="{{ route('admin.users.index') }}" class="px-8 py-4 bg-slate-100 text-slate-600 font-bold rounded-2xl hover:bg-slate-200 transition-all text-center">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function togglePwd(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon  = document.getElementById(iconId);
    input.type = input.type === 'password' ? 'text' : 'password';
    icon.className = input.type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
}
</script>
@endpush
@endsection
