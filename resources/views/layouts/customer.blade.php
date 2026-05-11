<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#1E1E1E">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Menu Kedai') — Kedai Wasis</title>
    <link rel="icon" type="image/png" href="{{ asset('images/kedai wasis.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style> 
        :root {
            --brand-primary: #1E1E1E;
            --brand-secondary: #FFD60A;
            --brand-accent: #9D4EDD;
        }
        body { font-family: 'Outfit', sans-serif; } 
    </style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased pb-24 overflow-x-hidden">

    <!-- Premium Header -->
    <header class="sticky top-0 z-50 bg-white border-b border-slate-100 shadow-sm shrink-0">
        <div class="max-w-6xl mx-auto px-4 lg:px-12 py-3 lg:py-4 flex items-center justify-between">
            <div class="flex items-center gap-2 lg:gap-3">
                <div class="w-10 h-10 lg:w-12 lg:h-12 bg-white border border-slate-100 rounded-xl lg:rounded-2xl flex items-center justify-center shadow-sm p-1.5 lg:p-2">
                    <img src="{{ asset('images/kedai wasis.png') }}" alt="Logo" class="w-full h-full object-contain">
                </div>
                <div>
                    <h1 class="font-black text-slate-900 leading-none text-base lg:text-lg">Kedai Wasis</h1>
                    <p class="hidden sm:block text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">Self-Service Menu</p>
                </div>
            </div>
            <div class="bg-brand-secondary text-brand-primary px-3 py-1.5 lg:px-4 lg:py-2 rounded-lg lg:rounded-xl flex items-center gap-1.5 lg:gap-2 shadow-sm border border-brand-secondary/20">
                <i class="bi bi-geo-alt-fill text-xs lg:text-base"></i>
                <span class="font-extrabold text-[10px] lg:text-sm uppercase tracking-tight text-brand-primary whitespace-nowrap">Meja {{ $table->nama_meja }}</span>
            </div>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-6 lg:px-12 pt-10 pb-20 animate-in fade-in duration-500">
        <!-- Flash Messages & Validation Errors -->
        @if(session('success'))
            <script>window.onload = () => showToast("{{ session('success') }}", 'success');</script>
        @endif
        @if(session('error'))
            <script>window.onload = () => showToast("{{ session('error') }}", 'rose');</script>
        @endif
        @if($errors->any())
            <script>
                window.onload = () => {
                    @foreach($errors->all() as $error)
                        showToast("{{ $error }}", 'rose');
                    @endforeach
                }
            </script>
        @endif

        @yield('content')
    </main>

    <script>
        // Global Toast System
        function showToast(message, type = 'success') {
            const id = 'toast-' + Math.random().toString(36).substr(2, 9);
            const bgColor = type === 'success' ? 'var(--brand-secondary, #FFD60A)' : 'var(--brand-accent, #9D4EDD)';
            const textColor = type === 'success' ? 'var(--brand-primary, #1E1E1E)' : '#FFFFFF';
            const icon = type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill';
            
            const html = `
                <div id="${id}" class="fixed top-10 right-6 lg:right-10 z-[2000] animate-in fade-in slide-in-from-right-10 duration-500">
                    <div style="background-color: ${bgColor}; color: ${textColor}" class="px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-3 border border-white/10">
                        <i class="bi ${icon} text-xl"></i>
                        <span class="font-bold text-sm">${message}</span>
                        <button onclick="document.getElementById('${id}').remove()" class="ml-4 opacity-50 hover:opacity-100"><i class="bi bi-x-lg"></i></button>
                    </div>
                </div>
            `;
            document.body.insertAdjacentHTML('beforeend', html);
            setTimeout(() => document.getElementById(id)?.remove(), 5000);
        }
    </script>
    @stack('scripts')
</body>
</html>
