@extends('layouts.admin')

@section('title', 'Edit Staff')
@section('page-title', 'Edit Pengguna')
@section('page-subtitle', 'Perbarui data dan akses akun staff.')

@section('topbar-actions')
<a href="{{ route('admin.users.index') }}" class="flex items-center gap-2 px-5 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl font-bold text-sm hover:bg-slate-50 transition-all">
    <i class="bi bi-arrow-left"></i> Kembali
</a>
@endsection

@section('content')
<div class="max-w-2xl">
    <div class="bg-white p-8 lg:p-10 rounded-[2rem] border border-slate-200 shadow-sm">

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

        {{-- User Identity Badge --}}
        <div class="flex items-center gap-4 mb-8 p-4 bg-slate-50 rounded-2xl border border-slate-100">
            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=6366f1&color=fff&size=80"
                class="w-14 h-14 rounded-2xl shadow-sm">
            <div>
                <div class="text-slate-900 font-extrabold text-base">{{ $user->name }}</div>
                <div class="text-slate-400 text-xs font-bold mt-0.5">{{ $user->email }}</div>
                <span @class([
                    'inline-flex mt-1.5 px-3 py-0.5 rounded-full text-[9px] font-black uppercase tracking-widest',
                    'bg-brand-primary/20 text-brand-primary' => $user->role == 'admin',
                    'bg-slate-100 text-slate-600' => $user->role == 'kasir'
                ])>{{ $user->role == 'admin' ? 'Administrator' : 'Staff Kasir' }}</span>
            </div>
        </div>

        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-6">
            @csrf @method('PUT')

            {{-- Full Name --}}
            <div>
                <label class="block text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-2.5 ml-1">Nama Lengkap <span class="text-brand-primary">*</span></label>
                <input type="text" name="name"
                    class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 font-bold text-slate-900 focus:ring-2 focus:ring-brand-primary focus:border-brand-primary transition-all @error('name') border-brand-primary/30 bg-brand-primary/5 @enderror"
                    value="{{ old('name', $user->name) }}" required>
                @error('name') <p class="text-brand-primary text-[10px] font-bold mt-2 ml-1 uppercase tracking-widest">{{ $message }}</p> @enderror
            </div>

            {{-- Email --}}
            <div>
                <label class="block text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-2.5 ml-1">Alamat Email <span class="text-brand-primary">*</span></label>
                <div class="relative">
                    <i class="bi bi-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 text-sm"></i>
                    <input type="email" name="email"
                        class="w-full bg-slate-50 border border-slate-200 rounded-2xl pl-11 pr-5 py-4 font-bold text-slate-900 focus:ring-2 focus:ring-brand-primary focus:border-brand-primary transition-all @error('email') border-brand-primary/30 bg-brand-primary/5 @enderror"
                        value="{{ old('email', $user->email) }}" required>
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
                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Administrator</option>
                            <option value="kasir" {{ $user->role == 'kasir' ? 'selected' : '' }}>Staff Kasir</option>
                        </select>
                        <i class="bi bi-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 text-xs pointer-events-none"></i>
                    </div>
                </div>

                {{-- New Password --}}
                <div>
                    <label class="block text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-2.5 ml-1">Password Baru <span class="text-slate-300 font-medium lowercase">(opsional)</span></label>
                    <div class="relative">
                        <i class="bi bi-key absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 text-sm"></i>
                        <input type="password" name="password" id="passwordField"
                            class="w-full bg-slate-50 border border-slate-200 rounded-2xl pl-11 pr-11 py-4 font-bold text-slate-900 focus:ring-2 focus:ring-brand-primary focus:border-brand-primary transition-all placeholder:text-slate-300"
                            placeholder="Kosongkan jika tidak diubah">
                        <button type="button" onclick="togglePwd('passwordField','eyeIconEdit')" tabindex="-1"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 hover:text-slate-600 transition-colors">
                            <i class="bi bi-eye" id="eyeIconEdit"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-50 flex flex-col sm:flex-row gap-4">
                <button type="submit" class="flex-1 bg-brand-primary text-white font-extrabold py-4.5 rounded-2xl hover:opacity-90 active:scale-[0.98] transition-all shadow-xl shadow-brand-primary/10">
                    <i class="bi bi-check2-circle mr-2"></i>Update Akun
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
