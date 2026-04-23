@extends('layouts.admin')

@section('title', 'Detail Meja & QR')
@section('page-title', 'Table QR Code')
@section('page-subtitle', 'Scan this code to access the self-service menu.')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
        <!-- QR Display Card (Printable Area) -->
        <div id="printable-qr" class="bg-white p-12 rounded-[3rem] border border-slate-200 shadow-sm flex flex-col items-center justify-center text-center relative overflow-hidden group">
            <!-- Decoration -->
            <div class="absolute top-0 left-0 w-full h-2 bg-indigo-600"></div>
            
            <div class="w-full aspect-square bg-slate-50 rounded-[2.5rem] border-2 border-dashed border-slate-200 flex items-center justify-center mb-10 overflow-hidden p-8 relative">
                <div class="absolute inset-0 opacity-5 pointer-events-none" style="background-image: radial-gradient(#6366f1 2px, transparent 2px); background-size: 20px 20px;"></div>
                
                <div class="bg-white p-6 rounded-[2rem] shadow-2xl relative z-10">
                    @php
                        $qrUrl = "https://quickchart.io/qr?text=" . urlencode(route('customer.menu', $table->qr_token)) . "&size=300&margin=1&ecLevel=H";
                    @endphp
                    <img src="{{ $qrUrl }}" class="w-[280px] h-[280px]" alt="QR Code">
                </div>
            </div>

            <div class="mb-10">
                <h3 class="text-slate-900 font-black text-4xl tracking-tighter mb-2">{{ strtoupper($table->nama_meja) }}</h3>
                <div class="flex items-center justify-center gap-3">
                    <span class="w-10 h-px bg-slate-200"></span>
                    <p class="text-slate-400 text-[10px] font-black uppercase tracking-[0.3em]">Scan to Order</p>
                    <span class="w-10 h-px bg-slate-200"></span>
                </div>
            </div>

            <div class="flex flex-col gap-3 w-full no-print">
                <button onclick="window.print()" class="w-full bg-slate-900 text-white font-black py-5 rounded-2xl flex items-center justify-center gap-3 hover:bg-indigo-600 transition-all shadow-xl shadow-slate-200 group/btn">
                    <i class="bi bi-printer-fill text-xl group-hover/btn:scale-110 transition-transform"></i> Print Table Sticker
                </button>
            </div>
        </div>

        <!-- Table Analytics & Info -->
        <div class="space-y-8 no-print">
            <!-- Stats -->
            <div class="bg-white p-10 rounded-[3rem] border border-slate-200 shadow-sm">
                <h4 class="text-slate-900 font-black text-xl mb-8 flex items-center gap-3">
                    <span class="w-2 h-6 bg-indigo-600 rounded-full"></span>
                    Operational Stats
                </h4>
                <div class="grid grid-cols-2 gap-6">
                    <div class="p-6 bg-slate-50 rounded-3xl border border-slate-100">
                        <div class="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-2">Total Service</div>
                        <div class="text-slate-900 font-black text-2xl tracking-tight">{{ $table->orders_count }} Orders</div>
                    </div>
                    <div class="p-6 bg-slate-50 rounded-3xl border border-slate-100">
                        <div class="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-2">Setup Cap.</div>
                        <div class="text-slate-900 font-black text-2xl tracking-tight">{{ $table->kapasitas }} Seats</div>
                    </div>
                </div>
            </div>

            <!-- Direct Link -->
            <div class="bg-indigo-600 p-10 rounded-[3rem] text-white shadow-2xl shadow-indigo-100 relative overflow-hidden">
                <div class="relative z-10">
                    <h4 class="font-black text-xl mb-6">Digital Link Access</h4>
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/10 mb-8">
                        <code class="text-indigo-100 text-[10px] font-bold break-all block leading-relaxed opacity-80">
                            {{ route('customer.menu', $table->qr_token) }}
                        </code>
                    </div>
                    <button onclick="navigator.clipboard.writeText('{{ route('customer.menu', $table->qr_token) }}'); showToast('Link copied to clipboard!')" class="w-full bg-white text-indigo-600 font-black py-4 rounded-xl transition-all hover:bg-indigo-50 flex items-center justify-center gap-2 shadow-lg shadow-indigo-700/20">
                        <i class="bi bi-link-45deg text-xl"></i> Copy Link to Clipboard
                    </button>
                </div>
                <div class="absolute -bottom-12 -right-12 w-48 h-48 bg-white/10 rounded-full blur-3xl"></div>
            </div>

            <a href="{{ route('admin.tables.index') }}" class="flex items-center justify-center gap-2 text-slate-400 font-black text-xs uppercase tracking-widest hover:text-indigo-600 transition-colors py-4">
                <i class="bi bi-arrow-left"></i> Back to Management
            </a>
        </div>
    </div>
</div>

<style>
    @media print {
        body * { visibility: hidden; background: white !important; }
        #printable-qr, #printable-qr * { visibility: visible; }
        #printable-qr { 
            position: absolute; 
            left: 50%; 
            top: 50%; 
            transform: translate(-50%, -50%); 
            width: 100%; 
            border: none !important; 
            box-shadow: none !important; 
        }
        .no-print { display: none !important; }
    }
</style>
@endsection
