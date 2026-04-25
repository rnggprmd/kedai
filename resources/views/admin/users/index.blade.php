@extends('layouts.admin')

@section('title', 'Manajemen Staf')
@section('page-title', 'Staf & Akses')
@section('page-subtitle', 'Kelola hak akses dan profil anggota staf restoran Anda.')

@section('content')
<div class="mb-10 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
    {{-- Unified Filter Bar (Matching Menus Style) --}}
    <div class="bg-white p-1.5 rounded-[1.5rem] sm:rounded-full border border-slate-200 shadow-sm flex flex-col sm:flex-row items-stretch sm:items-center gap-2 w-full max-w-2xl transition-all focus-within:ring-4 focus-within:ring-brand-accent/5 focus-within:border-brand-accent">
        {{-- Search Section --}}
        <div class="relative flex-1 group">
            <i class="bi bi-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-brand-accent transition-colors text-sm"></i>
            <input type="text" id="userSearch" placeholder="Cari staff..." 
                class="w-full bg-transparent border-none focus:outline-none focus:ring-0 pl-12 pr-4 py-2.5 text-sm font-bold text-slate-900 placeholder:text-slate-300 outline-none">
        </div>

        {{-- Subtle Divider (Hidden on Mobile) --}}
        <div class="w-px h-6 bg-slate-100 hidden sm:block"></div>

        {{-- Role Filter Section --}}
        <div class="flex items-center gap-1 px-1 py-1 sm:py-0 overflow-x-auto no-scrollbar">
            <button onclick="filterUsers('all')" data-role-filter="all" class="role-btn flex-none px-6 py-2 rounded-full text-[9px] sm:text-[10px] font-black uppercase tracking-widest transition-all bg-brand-accent text-white shadow-lg shadow-brand-accent/20">
                Semua
            </button>
            <button onclick="filterUsers('admin')" data-role-filter="admin" class="role-btn flex-none px-6 py-2 rounded-full text-[9px] sm:text-[10px] font-black uppercase tracking-widest transition-all text-slate-400 hover:text-slate-600 hover:bg-slate-50">
                Admin
            </button>
            <button onclick="filterUsers('kasir')" data-role-filter="kasir" class="role-btn flex-none px-6 py-2 rounded-full text-[9px] sm:text-[10px] font-black uppercase tracking-widest transition-all text-slate-400 hover:text-slate-600 hover:bg-slate-50">
                Kasir
            </button>
        </div>
    </div>

    {{-- Quick Action Button --}}
    <button onclick="openCreateModal()" class="w-full lg:w-auto bg-brand-primary text-white px-8 py-3.5 rounded-full font-black text-xs uppercase tracking-widest hover:opacity-90 active:scale-95 transition-all shadow-xl shadow-brand-primary/20 flex items-center justify-center gap-3 shrink-0">
        <i class="bi bi-person-plus-fill text-lg"></i>
        Tambah Staff
    </button>
</div>

<!-- Premium Table Card -->
<div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse min-w-[800px]">
            <thead>
                <tr class="bg-slate-50/50">
                    <th class="px-8 py-5 text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] border-b border-slate-100">Anggota Staf</th>
                    <th class="px-8 py-5 text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] border-b border-slate-100">Peran</th>
                    <th class="px-8 py-5 text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] border-b border-slate-100">Status</th>
                    <th class="px-8 py-5 text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] border-b border-slate-100 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($users as $user)
                <tr class="user-row hover:bg-slate-50/50 transition-colors group" 
                    data-role="{{ $user->role }}" 
                    data-search="{{ strtolower($user->name . ' ' . $user->email) }}">
                    <td class="px-8 py-5">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl overflow-hidden shadow-sm border-2 border-white bg-slate-100 flex-shrink-0">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=f1f5f9&color=64748b&size=100" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <div class="text-slate-900 font-black text-base tracking-tight leading-tight">{{ $user->name }}</div>
                                <div class="text-slate-400 text-[11px] font-medium">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-5">
                        @if($user->role == 'admin')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-white text-brand-primary rounded-lg text-[9px] font-black uppercase tracking-wider border border-slate-50 shadow-sm">
                            <i class="bi bi-shield-check"></i> Admin
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1.5 text-brand-primary font-black text-[10px] uppercase tracking-widest">
                            <span class="w-1.5 h-1.5 bg-brand-secondary rounded-full animate-pulse"></span> Kasir
                        </span>
                        @endif
                    </td>
                    <td class="px-8 py-5">
                        <div class="flex items-center gap-2">
                            <span @class([
                                'w-1.5 h-1.5 rounded-full',
                                'bg-brand-secondary animate-pulse' => $user->is_active,
                                'bg-slate-300' => !$user->is_active
                            ])></span>
                            <span class="text-[10px] font-black uppercase tracking-widest {{ $user->is_active ? 'text-slate-900' : 'text-slate-400' }}">
                                {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                    </td>
                    <td class="px-8 py-5 text-right">
                        <div class="flex justify-end gap-2">
                            <button onclick="openEditModal({{ json_encode($user) }})" class="w-10 h-10 bg-slate-50 text-slate-400 rounded-xl flex items-center justify-center hover:bg-brand-accent hover:text-white transition-all shadow-sm">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            @if(auth()->id() !== $user->id)
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Hapus staff ini?')" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-10 h-10 bg-slate-50 text-slate-400 rounded-xl flex items-center justify-center hover:bg-brand-primary hover:text-white transition-all shadow-sm">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Staff Modal -->
<div id="userModal" class="fixed inset-0 z-[9999] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-md transition-opacity duration-300 opacity-0" id="modalBackdrop" onclick="closeModal()"></div>
    
    <div class="relative bg-white w-full max-w-[500px] max-h-[90vh] flex flex-col rounded-3xl shadow-[0_25px_80px_-15px_rgba(0,0,0,0.4)] overflow-hidden transform transition-all duration-300 scale-95 opacity-0" id="modalContent">
        <!-- Modal Header -->
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50 shrink-0">
            <div>
                <h3 id="modalTitle" class="text-slate-900 font-black text-xl tracking-tight leading-none mb-1">Undang Staf</h3>
                <p class="text-slate-400 text-[9px] font-bold uppercase tracking-widest">Manajemen Akses Tim</p>
            </div>
            <button type="button" onclick="closeModal()" class="w-10 h-10 bg-white border border-slate-200 text-slate-400 rounded-xl flex items-center justify-center hover:bg-brand-primary hover:text-white transition-all shadow-sm">
                <i class="bi bi-x-lg text-xs"></i>
            </button>
        </div>
        
        <!-- Modal Body (Scrollable) -->
        <div class="flex-1 overflow-y-auto p-6 md:p-8 custom-scrollbar bg-white">
            <form id="userForm" action="" method="POST">
                @csrf
                <input type="hidden" id="methodField" name="_method" value="POST">
                
                <div class="space-y-6">
                    <!-- Name -->
                    <div>
                        <label class="block text-slate-500 text-[8px] font-black uppercase tracking-widest mb-2 ml-1">Nama Lengkap <span class="text-brand-primary">*</span></label>
                        <input type="text" name="name" id="user_name" required 
                            class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 font-bold text-slate-900 focus:ring-4 focus:ring-brand-accent/5 focus:border-brand-accent transition-all text-sm"
                            placeholder="cth. John Doe">
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-slate-500 text-[8px] font-black uppercase tracking-widest mb-2 ml-1">Alamat Email <span class="text-brand-primary">*</span></label>
                        <input type="email" name="email" id="user_email" required 
                            class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 font-bold text-slate-900 focus:ring-4 focus:ring-brand-accent/5 focus:border-brand-accent transition-all text-sm"
                            placeholder="cth. john@example.com">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-slate-500 text-[8px] font-black uppercase tracking-widest mb-2 ml-1">Peran <span class="text-brand-primary">*</span></label>
                            <select name="role" id="user_role" required
                                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 font-bold text-slate-900 focus:ring-4 focus:ring-brand-accent/5 focus:border-brand-accent transition-all text-sm appearance-none">
                                <option value="kasir">Staf Kasir</option>
                                <option value="admin">Administrator</option>
                            </select>
                        </div>
                        <div id="statusWrapper">
                            <label class="block text-slate-500 text-[8px] font-black uppercase tracking-widest mb-2 ml-1">Status</label>
                            <select name="is_active" id="user_is_active"
                                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 font-bold text-slate-900 focus:ring-4 focus:ring-brand-accent/5 focus:border-brand-accent transition-all text-sm appearance-none">
                                <option value="1">Aktif</option>
                                <option value="0">Nonaktif</option>
                            </select>
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="space-y-4 pt-4 border-t border-slate-50">
                        <div>
                            <label class="block text-slate-500 text-[8px] font-black uppercase tracking-widest mb-2 ml-1">
                                Password <span id="passHint" class="text-slate-300 font-bold lowercase"></span>
                            </label>
                            <input type="password" name="password" id="user_password"
                                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 font-bold text-slate-900 focus:ring-4 focus:ring-brand-accent/5 focus:border-brand-accent transition-all text-sm"
                                placeholder="••••••••">
                        </div>
                        <div>
                            <label class="block text-slate-500 text-[8px] font-black uppercase tracking-widest mb-2 ml-1">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" id="user_password_confirmation"
                                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 font-bold text-slate-900 focus:ring-4 focus:ring-brand-accent/5 focus:border-brand-accent transition-all text-sm"
                                placeholder="••••••••">
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Modal Footer -->
        <div class="p-6 border-t border-slate-100 bg-slate-50/50 shrink-0">
            <button type="submit" form="userForm" class="w-full bg-brand-primary text-brand-secondary font-black py-4 rounded-xl hover:opacity-90 active:scale-95 transition-all shadow-xl shadow-brand-primary/20 flex items-center justify-center gap-2 text-xs">
                <i class="bi bi-check2-circle text-base"></i>
                Simpan Staff
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openCreateModal() {
        document.getElementById('modalTitle').innerText = 'Undang Staf';
        document.getElementById('userForm').action = "{{ route('admin.users.store') }}";
        document.getElementById('methodField').value = 'POST';
        document.getElementById('statusWrapper').classList.add('hidden');
        document.getElementById('passHint').innerText = '(wajib)';
        
        document.getElementById('user_name').value = '';
        document.getElementById('user_email').value = '';
        document.getElementById('user_role').value = 'kasir';
        document.getElementById('user_password').value = '';
        document.getElementById('user_password_confirmation').value = '';
        
        showModal();
    }

    function openEditModal(data) {
        document.getElementById('modalTitle').innerText = 'Edit Profil';
        document.getElementById('userForm').action = "/admin/users/" + data.id;
        document.getElementById('methodField').value = 'PATCH';
        document.getElementById('statusWrapper').classList.remove('hidden');
        document.getElementById('passHint').innerText = '(kosongkan jika tidak diganti)';
        
        document.getElementById('user_name').value = data.name;
        document.getElementById('user_email').value = data.email;
        document.getElementById('user_role').value = data.role;
        document.getElementById('user_is_active').value = data.is_active ? '1' : '0';
        document.getElementById('user_password').value = '';
        document.getElementById('user_password_confirmation').value = '';
        
        showModal();
    }

    function showModal() {
        const modal = document.getElementById('userModal');
        const backdrop = document.getElementById('modalBackdrop');
        const content = document.getElementById('modalContent');
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            backdrop.classList.remove('opacity-0');
            backdrop.classList.add('opacity-100');
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        const modal = document.getElementById('userModal');
        const backdrop = document.getElementById('modalBackdrop');
        const content = document.getElementById('modalContent');
        
        backdrop.classList.remove('opacity-100');
        backdrop.classList.add('opacity-0');
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }, 300);
    }

    // Client-side filtering
    const userSearch = document.getElementById('userSearch');
    let currentRoleFilter = 'all';

    function applyFilters() {
        const query = userSearch.value.toLowerCase();
        const rows = document.querySelectorAll('.user-row');

        rows.forEach(row => {
            const searchData = row.dataset.search;
            const role = row.dataset.role;
            
            const matchSearch = searchData.includes(query);
            const matchRole = currentRoleFilter === 'all' || role === currentRoleFilter;

            row.classList.toggle('hidden', !(matchSearch && matchRole));
        });
    }

    userSearch.addEventListener('input', applyFilters);

    window.filterUsers = function(role) {
        currentRoleFilter = role;
        
        document.querySelectorAll('.role-btn').forEach(btn => {
            if (btn.dataset.roleFilter === role) {
                btn.classList.add('bg-brand-accent', 'text-white', 'shadow-lg', 'shadow-brand-accent/20');
                btn.classList.remove('text-slate-400', 'hover:text-slate-600', 'hover:bg-slate-50');
                btn.style.backgroundColor = 'var(--brand-accent)';
                btn.style.color = 'white';
            } else {
                btn.classList.remove('bg-brand-accent', 'text-white', 'shadow-lg', 'shadow-brand-accent/20');
                btn.classList.add('text-slate-400', 'hover:text-slate-600', 'hover:bg-slate-50');
                btn.style.backgroundColor = 'transparent';
                btn.style.color = '';
            }
        });

        applyFilters();
    }
</script>

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endpush
@endsection
