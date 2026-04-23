@extends('layouts.admin')

@section('title', 'Edit Staff')
@section('page-title', 'Modify User')
@section('page-subtitle', 'Update staff credentials and access roles.')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white p-8 lg:p-10 rounded-[2rem] border border-slate-200 shadow-sm">
        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-8">
            @csrf @method('PUT')
            
            <div class="grid grid-cols-1 gap-8">
                <!-- Name -->
                <div>
                    <label class="block text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-3 ml-1">Full Name</label>
                    <input type="text" name="name" class="w-full bg-slate-50 border-none rounded-2xl px-5 py-4 font-bold text-slate-900 focus:ring-2 focus:ring-indigo-600 transition-all" value="{{ old('name', $user->name) }}" required>
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-3 ml-1">Email Address</label>
                    <input type="email" name="email" class="w-full bg-slate-50 border-none rounded-2xl px-5 py-4 font-bold text-slate-900 focus:ring-2 focus:ring-indigo-600 transition-all" value="{{ old('email', $user->email) }}" required>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Role -->
                    <div>
                        <label class="block text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-3 ml-1">Role Access</label>
                        <select name="role" class="w-full bg-slate-50 border-none rounded-2xl px-5 py-4 font-bold text-slate-900 focus:ring-2 focus:ring-indigo-600 appearance-none transition-all" required>
                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Administrator</option>
                            <option value="kasir" {{ $user->role == 'kasir' ? 'selected' : '' }}>Staff Cashier</option>
                        </select>
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-3 ml-1">New Password (Optional)</label>
                        <input type="password" name="password" class="w-full bg-slate-50 border-none rounded-2xl px-5 py-4 font-bold text-slate-900 focus:ring-2 focus:ring-indigo-600 transition-all" placeholder="Leave blank to keep current">
                    </div>
                </div>
            </div>

            <div class="pt-8 border-t border-slate-50 flex flex-col sm:flex-row gap-4">
                <button type="submit" class="flex-1 bg-indigo-600 text-white font-extrabold py-5 rounded-2xl hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-200">
                    Update Account
                </button>
                <a href="{{ route('admin.users.index') }}" class="px-10 py-5 bg-slate-100 text-slate-600 font-bold rounded-2xl hover:bg-slate-200 transition-all text-center">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
