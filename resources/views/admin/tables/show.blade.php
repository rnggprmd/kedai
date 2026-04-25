@extends('layouts.admin')

@section('title', 'Detail Meja & QR')
@section('page-title', 'Table QR Code')
@section('page-subtitle', 'Scan this code to access the self-service menu.')

@section('topbar-actions')
<a href="{{ route('admin.tables.index') }}" class="bg-white text-slate-400 px-6 py-3 rounded-full font-black text-xs uppercase tracking-widest hover:text-brand-primary transition-all shadow-sm border border-slate-100 flex items-center gap-2">
    <i class="bi bi-arrow-left"></i> Kembali
</a>
@endsection

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-stretch">
        
        <!-- Left Column: The QR Sticker -->
        <div class="flex flex-col h-full">
            <div id="printable-qr" class="bg-white rounded-[2.5rem] border border-slate-200 shadow-sm overflow-hidden relative group transition-all duration-700 hover:border-brand-accent/20 flex flex-col h-full">
                <!-- Premium Decoration -->
                <div class="absolute top-0 left-0 w-full h-2" style="background: linear-gradient(90deg, #240046 0%, #3C096C 50%, #9D4EDD 100%);"></div>
                
                <div class="p-10 sm:pt-12 sm:px-12 sm:pb-2 flex flex-col items-center flex-1 justify-end">
                    <!-- Spacer to push content down -->
                    <div class="flex-1"></div>
                    
                    <!-- QR Wrapper -->
                    <div class="w-full max-w-[300px] aspect-square bg-slate-50 rounded-[2.5rem] border-2 border-dashed border-slate-200 flex items-center justify-center mb-6 overflow-hidden p-6 relative shrink-0">
                        <div class="absolute inset-0 opacity-10 pointer-events-none" style="background-image: radial-gradient(#3C096C 1px, transparent 1px); background-size: 15px 15px;"></div>
                        
                        <div class="bg-white p-5 rounded-[2rem] shadow-[0_20px_50px_rgba(0,0,0,0.1)] relative z-10 transform group-hover:scale-105 transition-transform duration-700">
                            @php
                                $qrUrl = "https://quickchart.io/qr?text=" . urlencode(route('customer.menu', $table->qr_token)) . "&size=350&margin=1&ecLevel=H";
                            @endphp
                            <img src="{{ $qrUrl }}" class="w-full h-full" alt="QR Code">
                        </div>
                    </div>

                    <!-- Table Branding -->
                    <div class="text-center">
                        <h3 class="text-slate-900 font-black text-5xl tracking-tighter mb-2 leading-none">{{ strtoupper($table->nama_meja) }}</h3>
                        <div class="flex items-center justify-center gap-4 text-slate-400">
                            <div class="h-px w-8 bg-slate-100"></div>
                            <p class="text-[11px] font-bold uppercase tracking-[0.4em] text-brand-accent">Scan To Order</p>
                            <div class="h-px w-8 bg-slate-100"></div>
                        </div>
                    </div>
                </div>

                <!-- Print Action Overaly (No Print) -->
                <div class="p-6 bg-slate-50/50 border-t border-slate-100 no-print">
                    <a href="{{ route('admin.tables.qr_pdf', $table->id) }}" class="w-full bg-brand-primary text-white font-black py-4 rounded-xl flex items-center justify-center gap-3 hover:opacity-90 transition-all shadow-xl shadow-brand-primary/20 group/btn">
                        <i class="bi bi-file-earmark-pdf-fill text-lg group-hover/btn:scale-110 transition-transform"></i> Download QR (PDF)
                    </a>
                </div>
            </div>
        </div>

        <!-- Right Column: Stats & Guide -->
        <div class="flex flex-col gap-8 h-full no-print">
            
            <!-- Stats Bento Grid -->
            <div class="grid grid-cols-2 gap-6">
                <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm hover:border-brand-accent/30 transition-all flex flex-col items-center text-center">
                    <div class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center text-brand-accent mb-4">
                        <i class="bi bi-receipt-cutoff text-2xl"></i>
                    </div>
                    <div>
                        <div class="text-slate-400 text-[9px] font-black uppercase tracking-[0.2em] mb-1">Lifetime Orders</div>
                        <div class="text-slate-900 font-black text-2xl tracking-tight">{{ number_format($table->orders_count) }}</div>
                    </div>
                </div>
                <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm hover:border-brand-secondary/30 transition-all flex flex-col items-center text-center">
                    <div class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center text-brand-secondary mb-4">
                        <i class="bi bi-people-fill text-2xl"></i>
                    </div>
                    <div>
                        <div class="text-slate-400 text-[9px] font-black uppercase tracking-[0.2em] mb-1">Max Capacity</div>
                        <div class="text-slate-900 font-black text-2xl tracking-tight">{{ $table->kapasitas }} <span class="text-xs font-bold text-slate-300 uppercase">Pax</span></div>
                    </div>
                </div>
            </div>

            <!-- Unified Asset & Guide Card -->
            <div class="bg-white rounded-[2.5rem] p-10 border border-slate-200 shadow-sm relative overflow-hidden group hover:border-brand-accent/20 transition-all flex-1 flex flex-col justify-between">
                <div class="relative z-10 flex flex-col h-full">
                    <!-- Guide Text (Integrated) -->
                    <div class="flex items-start gap-5 mb-8">
                        <div class="w-12 h-12 bg-brand-accent/10 rounded-2xl flex items-center justify-center text-brand-accent shrink-0">
                            <i class="bi bi-info-circle-fill text-2xl"></i>
                        </div>
                        <div>
                            <h4 class="text-slate-900 font-black text-sm mb-1 uppercase tracking-tight">Panduan Penggunaan</h4>
                            <p class="text-slate-500 text-xs leading-relaxed font-medium">
                                Stiker QR dapat dicetak dan ditempel di meja untuk memudahkan pelanggan melakukan pemesanan mandiri secara digital.
                            </p>
                        </div>
                    </div>
                    
                    <div class="mt-auto">
                        <div class="bg-slate-50 border border-slate-100 rounded-2xl p-6 mb-6 group-hover:bg-slate-100/50 transition-all">
                            <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3">Public Access URL</div>
                            <div class="flex items-center gap-3">
                                <i class="bi bi-link-45deg text-brand-accent text-xl"></i>
                                <code class="text-slate-600 text-xs font-bold break-all flex-1">
                                    {{ route('customer.menu', $table->qr_token) }}
                                </code>
                            </div>
                        </div>

                        <button onclick="navigator.clipboard.writeText('{{ route('customer.menu', $table->qr_token) }}'); showToast('Link copied to clipboard!')" 
                                class="w-full bg-brand-accent text-white font-black py-4 rounded-xl hover:opacity-90 transition-all flex items-center justify-center gap-3 active:scale-95 shadow-xl shadow-brand-accent/20">
                            <i class="bi bi-copy text-lg"></i> Copy Access Link
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
        </div>
    </div>
</div>

<style>
    @media print {
        body { background: white !important; }
        body * { visibility: hidden; }
        #printable-qr, #printable-qr * { visibility: visible; }
        #printable-qr { 
            position: absolute; 
            left: 50%; 
            top: 45%; 
            transform: translate(-50%, -50%) scale(1.1); 
            width: 100%; 
            border: none !important; 
            box-shadow: none !important; 
        }
        .no-print { display: none !important; }
    }
</style>
@endsection
