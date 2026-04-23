@extends('layouts.kasir')
@section('title', 'Detail Menu')
@section('page-title', 'Detail Menu')
@section('content')
<div class="content-card" style="max-width:640px;">
    <div class="card-body">
        <div class="d-flex gap-3 mb-3">
            @if($menu->gambar)
            <img src="{{ Storage::url($menu->gambar) }}" style="width:120px;height:100px;object-fit:cover;border-radius:12px;">
            @else
            <div style="width:120px;height:100px;border-radius:12px;background:var(--surface-light);display:flex;align-items:center;justify-content:center;"><i class="bi bi-cup-straw" style="font-size:2rem;color:var(--text-muted);"></i></div>
            @endif
            <div>
                <h5 style="font-weight:700;">{{ $menu->nama }}</h5>
                <span class="badge-status badge-primary">{{ $menu->category->nama }}</span>
                <div class="mt-2" style="font-size:1.3rem;font-weight:800;color:var(--accent);">{{ $menu->formatted_harga }}</div>
            </div>
        </div>
        @if($menu->deskripsi)
        <p style="color:var(--text-muted);">{{ $menu->deskripsi }}</p>
        @endif
        <span class="badge-status {{ $menu->is_available ? 'badge-success' : 'badge-danger' }}">{{ $menu->is_available ? 'Tersedia' : 'Habis' }}</span>
        <div class="mt-4"><a href="{{ route('kasir.menus.index') }}" class="btn btn-outline-custom"><i class="bi bi-arrow-left me-1"></i> Kembali</a></div>
    </div>
</div>
@endsection
