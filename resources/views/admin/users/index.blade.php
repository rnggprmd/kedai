@extends('layouts.admin')

@section('title', 'Staff Management')
@section('page-title', 'Our Team')
@section('page-subtitle', 'Manage access rights and profiles for your restaurant staff.')

@section('content')
<div class="mb-12 flex flex-col sm:flex-row items-center justify-between gap-8">
    <div class="flex items-center gap-4">
        <div class="bg-white px-8 py-4 rounded-[2rem] border border-slate-200 shadow-sm">
            <span class="text-slate-400 text-[10px] font-bold uppercase tracking-widest block mb-1">Total Members</span>
            <span class="text-slate-900 font-black text-2xl tracking-tight">{{ $users->count() }}</span>
        </div>
    </div>
    
    <a href="{{ route('admin.users.create') }}" class="w-full sm:w-auto bg-indigo-600 text-white px-10 py-5 rounded-[2rem] font-extrabold flex items-center justify-center gap-3 hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-100">
        <i class="bi bi-person-plus-fill text-lg"></i> Invite Member
    </a>
</div>

<!-- Team Gallery Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
    @foreach($users as $user)
    <div class="group bg-white rounded-[3rem] border border-slate-200 shadow-sm hover:shadow-2xl hover:border-indigo-100 transition-all duration-500 overflow-hidden flex flex-col items-center text-center p-10">
        <!-- Profile Avatar -->
        <div class="relative mb-8">
            <div class="absolute inset-0 bg-indigo-600 rounded-[2.5rem] blur-2xl opacity-10 group-hover:opacity-30 transition-opacity"></div>
            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=6366f1&color=fff&size=128" 
                 class="relative w-32 h-32 rounded-[2.5rem] border-4 border-white shadow-xl group-hover:scale-110 transition-transform duration-500">
            <div class="absolute -bottom-2 -right-2 w-10 h-10 bg-white rounded-2xl flex items-center justify-center shadow-lg border border-slate-50">
                <i @class([
                    'bi text-xl',
                    'bi-shield-check text-indigo-600' => $user->role == 'admin',
                    'bi-person-badge text-amber-500' => $user->role == 'kasir'
                ])></i>
            </div>
        </div>

        <h3 class="text-slate-900 font-black text-xl tracking-tight mb-1">{{ $user->name }}</h3>
        <p class="text-slate-400 text-xs font-bold mb-6 truncate w-full">{{ $user->email }}</p>
        
        <div @class([
            'px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest mb-10',
            'bg-indigo-50 text-indigo-600' => $user->role == 'admin',
            'bg-amber-50 text-amber-600' => $user->role == 'kasir'
        ])>
            {{ $user->role == 'admin' ? 'Administrator' : 'Staff Cashier' }}
        </div>

        <!-- Action Links -->
        <div class="mt-auto w-full pt-8 border-t border-slate-50 flex items-center justify-center gap-4">
            <a href="{{ route('admin.users.edit', $user) }}" class="w-14 h-14 bg-slate-50 text-slate-400 rounded-2xl flex items-center justify-center hover:bg-indigo-600 hover:text-white transition-all group/btn shadow-sm">
                <i class="bi bi-pencil-square text-xl"></i>
            </a>
            @if(auth()->id() !== $user->id)
            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Remove this staff member?')" class="inline">
                @csrf @method('DELETE')
                <button class="w-14 h-14 bg-slate-50 text-slate-400 rounded-2xl flex items-center justify-center hover:bg-rose-600 hover:text-white transition-all group/btn shadow-sm">
                    <i class="bi bi-trash3 text-xl"></i>
                </button>
            </form>
            @endif
        </div>
    </div>
    @endforeach
</div>
@endsection
