@extends('layouts.app')

@push('styles')
<style>
    :root {
        --brand-primary: #1E1E1E;
        --brand-secondary: #FFD60A;
        --brand-accent: #9D4EDD;
    }
</style>
@endpush

@section('sidebar-nav')
    @include('layouts.kasir-nav')
@endsection
