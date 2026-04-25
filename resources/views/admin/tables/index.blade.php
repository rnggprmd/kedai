@extends('layouts.admin')

@section('title', 'Kelola Meja')
@section('page-title', 'Daftar Meja')
@section('page-subtitle', 'Kelola denah meja, kapasitas, dan status ketersediaan meja Anda.')

@section('content')
<div class="mb-10 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
    {{-- Unified Filter Bar --}}
    <div class="bg-white p-1.5 rounded-[1.5rem] sm:rounded-full border border-slate-200 shadow-sm flex flex-col sm:flex-row items-stretch sm:items-center gap-2 w-full max-w-xl transition-all focus-within:ring-4 focus-within:ring-brand-accent/5 focus-within:border-brand-accent">
        {{-- Search Section --}}
        <div class="relative flex-1 group">
            <i class="bi bi-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-brand-accent transition-colors text-sm"></i>
            <input type="text" id="tableSearch" placeholder="Cari meja (kode atau nama)..." 
                class="w-full bg-transparent border-none focus:outline-none focus:ring-0 pl-12 pr-4 py-2.5 text-sm font-bold text-slate-900 placeholder:text-slate-300 outline-none">
        </div>

        {{-- Subtle Divider (Hidden on Mobile) --}}
        <div class="w-px h-6 bg-slate-100 hidden sm:block"></div>

        {{-- Status Filter Section --}}
        <div class="flex items-center justify-around sm:justify-start gap-1 px-1 py-1 sm:py-0 border-t sm:border-t-0 border-slate-50">
            <button onclick="filterStatus('all')" data-status-filter="all" class="status-btn flex-1 sm:flex-none px-4 sm:px-6 py-2 rounded-full text-[9px] sm:text-[10px] font-black uppercase tracking-widest transition-all bg-brand-accent text-white shadow-lg shadow-brand-accent/20">
                Semua
            </button>
            <button onclick="filterStatus('active')" data-status-filter="active" class="status-btn flex-1 sm:flex-none px-4 sm:px-6 py-2 rounded-full text-[9px] sm:text-[10px] font-black uppercase tracking-widest transition-all text-slate-400 hover:text-slate-600 hover:bg-slate-50">
                Aktif
            </button>
            <button onclick="filterStatus('inactive')" data-status-filter="inactive" class="status-btn flex-1 sm:flex-none px-4 sm:px-6 py-2 rounded-full text-[9px] sm:text-[10px] font-black uppercase tracking-widest transition-all text-slate-400 hover:text-slate-600 hover:bg-slate-50">
                Non-Aktif
            </button>
        </div>
    </div>

    {{-- Quick Action Button --}}
    <button onclick="openCreateModal()" class="w-full lg:w-auto bg-brand-primary text-white px-8 py-3.5 rounded-full font-black text-xs uppercase tracking-widest hover:opacity-90 active:scale-95 transition-all shadow-xl shadow-brand-primary/20 flex items-center justify-center gap-3 shrink-0">
        <i class="bi bi-plus-circle-fill text-lg"></i>
        Tambah Meja
    </button>
</div>

<!-- Premium Table -->
<div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse min-w-[700px]">
            <thead>
                <tr class="bg-slate-50/50">
                    <th class="px-8 py-5 text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] border-b border-slate-100">Info Meja</th>
                    <th class="px-8 py-5 text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] border-b border-slate-100">Kapasitas</th>
                    <th class="px-8 py-5 text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] border-b border-slate-100">Pesanan Harian</th>
                    <th class="px-8 py-5 text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] border-b border-slate-100">Visibilitas</th>
                    <th class="px-8 py-5 text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] border-b border-slate-100 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($tables as $table)
                <tr class="table-row hover:bg-slate-50/50 transition-colors group" 
                    data-status="{{ $table->is_active ? 'active' : 'inactive' }}"
                    data-search="{{ strtolower($table->kode_meja . ' ' . $table->nama_meja) }}">
                    <td class="px-8 py-5">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center text-slate-400 group-hover:bg-brand-accent/10 group-hover:text-brand-accent transition-colors">
                                <i class="bi bi-door-open-fill text-lg"></i>
                            </div>
                            <div>
                                <div class="text-slate-900 font-black text-sm tracking-tight">{{ $table->nama_meja }}</div>
                                <div class="text-slate-400 text-[10px] font-bold uppercase tracking-widest">Kode: {{ $table->kode_meja }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-5">
                        <span class="text-slate-900 font-bold text-sm flex items-center gap-2">
                            <i class="bi bi-people-fill text-brand-secondary text-xs"></i>
                            {{ $table->kapasitas }} <span class="text-slate-400 text-[10px] uppercase font-black">Kursi</span>
                        </span>
                    </td>
                    <td class="px-8 py-5">
                        <div class="text-slate-900 font-bold text-sm">
                            {{ $table->orders_count ?? 0 }} <span class="text-slate-400 text-[10px] uppercase font-black">Pesanan</span>
                        </div>
                    </td>
                    <td class="px-8 py-5">
                        @if($table->is_active)
                            <span class="inline-flex items-center gap-1.5 text-brand-primary font-black text-[10px] uppercase tracking-widest">
                                <span class="w-1.5 h-1.5 bg-brand-secondary rounded-full animate-pulse"></span> Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 text-slate-400 font-black text-[10px] uppercase tracking-widest">
                                <span class="w-1.5 h-1.5 bg-slate-300 rounded-full"></span> Non-Aktif
                            </span>
                        @endif
                    </td>
                    <td class="px-8 py-5">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.tables.show', $table) }}" class="w-10 h-10 bg-slate-50 text-slate-400 rounded-xl flex items-center justify-center hover:bg-slate-900 hover:text-white transition-all shadow-sm" title="Lihat QR">
                                <i class="bi bi-qr-code"></i>
                            </a>
                            <button onclick="openEditModal({{ json_encode([
                                'id' => $table->id,
                                'kode_meja' => $table->kode_meja,
                                'nama_meja' => $table->nama_meja,
                                'kapasitas' => $table->kapasitas,
                                'is_active' => $table->is_active
                            ]) }})" class="w-10 h-10 bg-slate-50 text-slate-400 rounded-xl flex items-center justify-center hover:bg-brand-accent hover:text-white transition-all shadow-sm">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <form action="{{ route('admin.tables.destroy', $table) }}" method="POST" onsubmit="return confirm('Hapus meja ini?')" class="inline">
                                @csrf @method('DELETE')
                                <button class="w-10 h-10 bg-slate-50 text-slate-400 rounded-xl flex items-center justify-center hover:bg-brand-primary hover:text-white transition-all shadow-sm">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-8 py-20 text-center">
                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mx-auto mb-4">
                            <i class="bi bi-door-closed text-3xl"></i>
                        </div>
                        <p class="text-slate-400 font-bold text-sm">Belum ada data meja.</p>
                    </td>
                </tr>
                @endforelse
                {{-- Hidden Empty State for Search --}}
                <tr class="no-results hidden">
                    <td colspan="5" class="px-8 py-20 text-center">
                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mx-auto mb-4">
                            <i class="bi bi-search text-3xl"></i>
                        </div>
                        <p class="text-slate-400 font-bold text-sm">Tidak ada meja yang cocok.</p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Table Modal -->
<div id="tableModal" class="fixed inset-0 z-[9999] hidden flex items-center justify-center p-4">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-md transition-opacity duration-300 opacity-0" id="modalBackdrop" onclick="closeModal()"></div>
    
    <!-- Modal Content -->
    <div class="relative bg-white w-full max-w-[450px] max-h-[90vh] flex flex-col rounded-3xl shadow-[0_25px_80px_-15px_rgba(0,0,0,0.4)] overflow-hidden transform transition-all duration-300 scale-95 opacity-0" id="modalContent">
        <!-- Modal Header -->
        <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50 shrink-0">
            <div>
                <h3 id="modalTitle" class="text-slate-900 font-black text-xl tracking-tight leading-none mb-1">Tambah Meja</h3>
                <p class="text-slate-400 text-[9px] font-bold uppercase tracking-widest">Data Master Meja</p>
            </div>
            <button type="button" onclick="closeModal()" class="w-11 h-11 bg-white border border-slate-200 text-slate-400 rounded-xl flex items-center justify-center hover:bg-brand-primary hover:text-white transition-all shadow-sm">
                <i class="bi bi-x-lg text-sm"></i>
            </button>
        </div>
        
        <!-- Modal Body (Scrollable) -->
        <div class="flex-1 overflow-y-auto p-8 custom-scrollbar bg-white">
            <form id="tableForm" action="" method="POST">
                @csrf
                <input type="hidden" id="methodField" name="_method" value="POST">
                
                <div class="space-y-6">
                    <div>
                        <label class="block text-slate-500 text-[8px] font-black uppercase tracking-widest mb-2 ml-1">Kode Meja <span class="text-brand-primary">*</span></label>
                        <input type="text" name="kode_meja" id="tbl_kode" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 font-bold text-slate-900 focus:ring-4 focus:ring-brand-accent/5 focus:border-brand-accent transition-all placeholder:text-slate-300 text-sm"
                            placeholder="e.g. M01, MEJA-A, etc...">
                    </div>

                    <div>
                        <label class="block text-slate-500 text-[8px] font-black uppercase tracking-widest mb-2 ml-1">Nama Meja <span class="text-brand-primary">*</span></label>
                        <input type="text" name="nama_meja" id="tbl_nama" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 font-bold text-slate-900 focus:ring-4 focus:ring-brand-accent/5 focus:border-brand-accent transition-all placeholder:text-slate-300 text-sm"
                            placeholder="e.g. Meja Teras 1...">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-slate-500 text-[8px] font-black uppercase tracking-widest mb-2 ml-1">Kapasitas (Orang) <span class="text-brand-primary">*</span></label>
                            <input type="number" name="kapasitas" id="tbl_kapasitas" required min="1"
                                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 font-bold text-slate-900 focus:ring-4 focus:ring-brand-accent/5 focus:border-brand-accent transition-all text-sm"
                                placeholder="0">
                        </div>
                        <div>
                            <label class="block text-slate-500 text-[8px] font-black uppercase tracking-widest mb-2 ml-1">Status</label>
                            <div onclick="toggleStatus()" class="bg-slate-50 border border-slate-200 rounded-lg px-4 py-2 flex items-center justify-between h-[46px] cursor-pointer hover:bg-slate-100/50 transition-all select-none">
                                <span class="text-slate-900 font-bold text-[10px]" id="status_text">Aktif</span>
                                <div class="relative inline-flex items-center pointer-events-none">
                                    <input type="checkbox" name="is_active" id="tbl_is_active" value="1" checked class="sr-only peer">
                                    <div class="w-9 h-5 bg-slate-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-brand-secondary"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Modal Footer -->
        <div class="p-6 border-t border-slate-100 bg-slate-50/50 shrink-0">
            <button type="submit" form="tableForm" class="w-full bg-brand-primary text-brand-secondary font-black py-4 rounded-xl hover:opacity-90 active:scale-95 transition-all shadow-xl shadow-brand-primary/20 flex items-center justify-center gap-2 text-xs">
                <i class="bi bi-check2-circle text-base"></i>
                Simpan Meja
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openCreateModal() {
        document.getElementById('modalTitle').innerText = 'Tambah Meja';
        document.getElementById('tableForm').action = "{{ route('admin.tables.store') }}";
        document.getElementById('methodField').value = 'POST';
        
        document.getElementById('tbl_kode').value = '';
        document.getElementById('tbl_nama').value = '';
        document.getElementById('tbl_kapasitas').value = '';
        document.getElementById('tbl_is_active').checked = true;
        document.getElementById('status_text').innerText = 'Aktif';
        
        showModal();
    }

    function openEditModal(data) {
        document.getElementById('modalTitle').innerText = 'Edit Meja';
        document.getElementById('tableForm').action = "/admin/tables/" + data.id;
        document.getElementById('methodField').value = 'PATCH';
        
        document.getElementById('tbl_kode').value = data.kode_meja;
        document.getElementById('tbl_nama').value = data.nama_meja;
        document.getElementById('tbl_kapasitas').value = data.kapasitas;
        document.getElementById('tbl_is_active').checked = data.is_active;
        document.getElementById('status_text').innerText = data.is_active ? 'Aktif' : 'Non-Aktif';
        
        showModal();
    }

    function showModal() {
        const modal = document.getElementById('tableModal');
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
        const modal = document.getElementById('tableModal');
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

    function toggleStatus() {
        const checkbox = document.getElementById('tbl_is_active');
        checkbox.checked = !checkbox.checked;
        document.getElementById('status_text').innerText = checkbox.checked ? 'Aktif' : 'Non-Aktif';
    }

    // Client-side table search and status filter
    const tableSearch = document.getElementById('tableSearch');
    let currentStatusFilter = 'all';

    function updateTable() {
        const query = tableSearch.value.toLowerCase();
        const rows = document.querySelectorAll('.table-row');
        let hasResults = false;

        rows.forEach(row => {
            const searchText = row.dataset.search;
            const status = row.dataset.status;
            
            const matchSearch = searchText.includes(query);
            const matchStatus = currentStatusFilter === 'all' || status === currentStatusFilter;

            if (matchSearch && matchStatus) {
                row.classList.remove('hidden');
                hasResults = true;
            } else {
                row.classList.add('hidden');
            }
        });

        const emptyState = document.querySelector('.no-results');
        if (emptyState) {
            emptyState.classList.toggle('hidden', hasResults);
        }
    }

    tableSearch.addEventListener('input', updateTable);

    window.filterStatus = function(status) {
        currentStatusFilter = status;
        
        // Update UI
        document.querySelectorAll('.status-btn').forEach(btn => {
            if (btn.dataset.statusFilter === status) {
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

        updateTable();
    }

    // Close on Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeModal();
    });

    // Auto-open modal if there are validation errors
    @if($errors->any())
        window.addEventListener('DOMContentLoaded', () => {
            openCreateModal();
            // Re-fill with old data
            document.getElementById('tbl_kode').value = "{{ old('kode_meja') }}";
            document.getElementById('tbl_nama').value = "{{ old('nama_meja') }}";
            document.getElementById('tbl_kapasitas').value = "{{ old('kapasitas') }}";
        });
    @endif
</script>
@endpush

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
</style>
@endsection

