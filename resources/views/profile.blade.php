@extends('layouts.app')

@section('title', 'Profil Saya')
@section('page-title', 'Pengaturan Akun')
@section('page-subtitle', 'Kelola informasi pribadi dan keamanan akun Anda.')

@section('sidebar-nav')
    @if(auth()->user()->role == 'admin')
        @include('layouts.admin-nav')
    @else
        @include('layouts.kasir-nav')
    @endif
@endsection

@section('content')
<div class="max-w-4xl -mt-4 lg:-mt-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Profile Info Card -->
        <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm flex flex-col items-center text-center">
            <div class="relative mb-6">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=1E1E1E&color=fff&size=128" class="w-28 h-28 rounded-[2rem] shadow-xl border-4 border-white">
                <div class="absolute -bottom-1 -right-1 w-9 h-9 bg-brand-secondary text-brand-primary rounded-xl flex items-center justify-center border-2 border-white shadow-md">
                    <i class="bi bi-patch-check-fill text-lg"></i>
                </div>
            </div>
            <h3 class="text-slate-900 font-extrabold text-xl tracking-tight">{{ auth()->user()->name }}</h3>
            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-[9px] font-extrabold bg-brand-primary/10 text-brand-primary mt-2 tracking-widest uppercase">
                Akun {{ auth()->user()->role == 'admin' ? 'Administrator' : 'Kasir' }}
            </span>
            <div class="w-full mt-8 pt-6 border-t border-slate-100 space-y-3">
                <div class="flex justify-between items-center text-xs font-bold">
                    <span class="text-slate-400 uppercase tracking-widest text-[9px]">Email</span>
                    <span class="text-slate-900 truncate max-w-[150px]">{{ auth()->user()->email }}</span>
                </div>
                <div class="flex justify-between items-center text-xs font-bold">
                    <span class="text-slate-400 uppercase tracking-widest text-[9px]">Status</span>
                    <span class="text-emerald-600 uppercase tracking-wider text-[10px]">Aktif</span>
                </div>
                <div class="flex justify-between items-center text-xs font-bold">
                    <span class="text-slate-400 uppercase tracking-widest text-[9px]">Bergabung</span>
                    <span class="text-slate-900">{{ auth()->user()->created_at?->format('d M Y') ?? '-' }}</span>
                </div>
            </div>
        </div>

        <!-- Security / Update Form -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white p-8 lg:p-10 rounded-[2.5rem] border border-slate-200 shadow-sm">
                <h4 class="text-slate-900 font-extrabold text-lg mb-6">Perbarui Profil & Sandi</h4>
                
                @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-600 rounded-2xl p-4 mb-6">
                    <div class="font-bold text-xs mb-1 flex items-center gap-2">
                        <i class="bi bi-exclamation-triangle-fill"></i> Periksa kembali input Anda:
                    </div>
                    <ul class="text-xs list-disc list-inside space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('profile.update') }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-2 ml-1">Nama Lengkap</label>
                        <input type="text" name="name" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-900 focus:ring-4 focus:ring-brand-accent/5 focus:border-brand-accent transition-all text-sm outline-none" value="{{ old('name', auth()->user()->name) }}" required>
                    </div>

                    <div class="pt-4 border-t border-slate-100">
                        <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-4">Ubah Kata Sandi (Opsional)</p>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-slate-400 text-[9px] font-bold uppercase tracking-widest mb-2 ml-1">Kata Sandi Saat Ini</label>
                                <input type="password" name="current_password" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-900 focus:ring-4 focus:ring-brand-accent/5 focus:border-brand-accent transition-all text-sm outline-none" placeholder="Masukkan jika ingin mengganti sandi">
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-slate-400 text-[9px] font-bold uppercase tracking-widest mb-2 ml-1">Kata Sandi Baru</label>
                                    <input type="password" name="password" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-900 focus:ring-4 focus:ring-brand-accent/5 focus:border-brand-accent transition-all text-sm outline-none" placeholder="Min. 8 karakter">
                                </div>
                                <div>
                                    <label class="block text-slate-400 text-[9px] font-bold uppercase tracking-widest mb-2 ml-1">Konfirmasi Kata Sandi Baru</label>
                                    <input type="password" name="password_confirmation" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-900 focus:ring-4 focus:ring-brand-accent/5 focus:border-brand-accent transition-all text-sm outline-none" placeholder="Ulangi kata sandi baru">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex justify-end">
                        <button type="submit" class="bg-brand-primary text-brand-secondary font-black px-8 py-3.5 rounded-xl hover:opacity-90 active:scale-95 transition-all shadow-lg shadow-brand-primary/20 text-xs uppercase tracking-widest flex items-center gap-2">
                            <i class="bi bi-check2-circle text-base"></i>
                            <span>Simpan Perubahan</span>
                        </button>
                    </div>
                </form>
            </div>
            
            <div class="p-6 bg-brand-accent/5 rounded-[2rem] border border-brand-accent/15 flex items-center gap-4">
                <div class="w-10 h-10 bg-brand-accent text-white rounded-xl flex items-center justify-center text-lg shrink-0">
                    <i class="bi bi-shield-lock"></i>
                </div>
                <div class="flex-1">
                    <div class="text-slate-900 font-extrabold text-xs">Kiat Keamanan Akun</div>
                    <p class="text-slate-500 text-[11px] font-medium mt-0.5">Pastikan kata sandi Anda kuat, unik, dan selalu lakukan logout setelah menggunakan komputer kasir bersama.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
