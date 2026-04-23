@extends('layouts.app')

@section('title', 'My Profile')
@section('page-title', 'Account Settings')
@section('page-subtitle', 'Manage your personal information and security.')

@section('sidebar-nav')
    @if(auth()->user()->role == 'admin')
        @include('layouts.admin-nav') {{-- Misal jika navigasi dipisah ke partial --}}
    @else
        @include('layouts.kasir-nav')
    @endif
@endsection

@section('content')
{{-- Note: Karena saya baru saja merombak layouts/admin & kasir, saya akan pastikan navigasi tetap tampil --}}
<div class="max-w-4xl">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Profile Info Card -->
        <div class="bg-white p-8 rounded-[2rem] border border-slate-200 shadow-sm flex flex-col items-center text-center">
            <div class="relative mb-6">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=6366f1&color=fff&size=128" class="w-32 h-32 rounded-[2.5rem] shadow-2xl border-4 border-white">
                <div class="absolute -bottom-2 -right-2 w-10 h-10 bg-emerald-500 text-white rounded-2xl flex items-center justify-center border-4 border-white shadow-lg">
                    <i class="bi bi-patch-check-fill text-xl"></i>
                </div>
            </div>
            <h3 class="text-slate-900 font-extrabold text-2xl tracking-tight">{{ auth()->user()->name }}</h3>
            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-[10px] font-extrabold bg-indigo-50 text-indigo-600 mt-2 tracking-widest uppercase">
                {{ auth()->user()->role }} Account
            </span>
            <div class="w-full mt-8 pt-8 border-t border-slate-50 space-y-4">
                <div class="flex justify-between items-center text-sm font-bold">
                    <span class="text-slate-400 uppercase tracking-widest text-[10px]">Email</span>
                    <span class="text-slate-900">{{ auth()->user()->email }}</span>
                </div>
                <div class="flex justify-between items-center text-sm font-bold">
                    <span class="text-slate-400 uppercase tracking-widest text-[10px]">Member Since</span>
                    <span class="text-slate-900">{{ auth()->user()->created_at->format('M Y') }}</span>
                </div>
            </div>
        </div>

        <!-- Security / Update Form -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white p-8 lg:p-10 rounded-[2rem] border border-slate-200 shadow-sm">
                <h4 class="text-slate-900 font-extrabold text-lg mb-8">Update Information</h4>
                <form action="#" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-3 ml-1">Display Name</label>
                            <input type="text" class="w-full bg-slate-50 border-none rounded-2xl px-5 py-4 font-bold text-slate-900 focus:ring-2 focus:ring-indigo-600 transition-all" value="{{ auth()->user()->name }}">
                        </div>
                        <div>
                            <label class="block text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-3 ml-1">New Password</label>
                            <input type="password" class="w-full bg-slate-50 border-none rounded-2xl px-5 py-4 font-bold text-slate-900 focus:ring-2 focus:ring-indigo-600 transition-all" placeholder="••••••••">
                        </div>
                    </div>
                    <div class="pt-6 border-t border-slate-50">
                        <button type="submit" class="bg-slate-900 text-white font-extrabold px-8 py-4 rounded-2xl hover:bg-slate-800 transition-all shadow-xl shadow-slate-200">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
            
            <div class="p-6 bg-indigo-50 rounded-[2rem] border border-indigo-100 flex items-center gap-4">
                <div class="w-12 h-12 bg-indigo-600 text-white rounded-xl flex items-center justify-center text-xl">
                    <i class="bi bi-shield-lock"></i>
                </div>
                <div class="flex-1">
                    <div class="text-indigo-900 font-extrabold text-sm">Security Tip</div>
                    <p class="text-indigo-600/70 text-xs font-medium">Use a strong password and sign out if you are using a public computer.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
