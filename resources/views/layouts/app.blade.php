<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — KedaiPos Tailwind</title>
    
    <!-- Vite Assets (Tailwind 4.0) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Icons & Fonts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Outfit', sans-serif; }
        /* Smooth scroll for the whole page */
        html { scroll-behavior: smooth; }
        /* Custom scrollbar for sidebar */
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.1); border-radius: 10px; }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-900 antialiased overflow-x-hidden">

    <!-- Mobile Sidebar Overlay -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-[1050] hidden transition-opacity duration-300 opacity-0" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <aside id="sidebar" class="fixed top-0 left-0 bottom-0 w-[260px] z-[1100] flex flex-col p-6 transition-transform duration-300 ease-in-out -translate-x-full lg:translate-x-0" style="background: linear-gradient(180deg, #240046 0%, #3C096C 100%); border-right: 1px solid rgba(255,255,255,0.05);">
        <!-- Mobile Close Button -->
        <button class="lg:hidden absolute top-8 right-6 text-slate-500 hover:text-white transition-colors" onclick="toggleSidebar()">
            <i class="bi bi-x-lg text-xl"></i>
        </button>

        <!-- Logo -->
        <a href="{{ auth()->user()->role == 'admin' ? route('admin.dashboard') : route('kasir.dashboard') }}" class="flex items-center gap-3 mb-10 px-2 group">
            <div class="w-10 h-10 bg-brand-accent rounded-xl flex items-center justify-center text-white shadow-lg shadow-brand-accent/30 group-hover:scale-110 transition-transform">
                <i class="bi bi-lightning-charge-fill text-xl"></i>
            </div>
            <h5 class="text-white font-extrabold text-xl tracking-tight">KedaiPos</h5>
        </a>

        <!-- Navigation Menu -->
        <nav class="flex-1 overflow-y-auto custom-scrollbar -mx-2 px-2">
            @yield('sidebar-nav')
        </nav>

        <!-- User Profile Bottom -->
        <div class="mt-auto pt-6 border-t border-white/10 px-2">
            <div class="flex items-center gap-3 p-3 bg-white/5 rounded-2xl border border-white/5">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=3C096C&color=fff" class="w-10 h-10 rounded-xl shadow-sm">
                <div class="min-w-0">
                    <div class="text-white font-bold text-sm truncate">{{ auth()->user()->name }}</div>
                    <div class="text-white/80 text-[10px] font-bold uppercase tracking-wider">{{ auth()->user()->role == 'admin' ? 'Administrator' : 'Kasir' }}</div>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="lg:ml-[260px] min-h-screen flex flex-col">
        <!-- Header -->
        <header class="sticky top-0 z-[1000] bg-white/80 backdrop-blur-xl h-20 border-b border-slate-200 flex items-center justify-between px-6 lg:px-10">
            <div class="flex items-center gap-4">
                <button class="lg:hidden text-slate-600 hover:text-slate-900" onclick="toggleSidebar()">
                    <i class="bi bi-list text-3xl"></i>
                </button>
                <div class="hidden md:flex items-center gap-2 text-slate-400 font-bold text-xs uppercase tracking-widest">
                    <i class="bi bi-house-door"></i>
                    <span class="opacity-30">/</span>
                    <span class="text-slate-600">@yield('title', 'Dashboard')</span>
                </div>
            </div>

            <!-- Realtime WIB Clock -->
            <div class="hidden xl:flex items-center gap-4 px-5 py-2.5 bg-slate-50 rounded-2xl border border-slate-100">
                <div class="flex flex-col items-end">
                    <div id="realtime-clock" class="text-slate-900 font-black text-sm tracking-wider">00:00:00</div>
                    <div id="realtime-date" class="text-slate-400 text-[9px] font-bold uppercase tracking-widest">WIB — LOADING</div>
                </div>
                <div class="w-8 h-8 bg-brand-secondary/20 rounded-lg flex items-center justify-center text-brand-secondary">
                    <i class="bi bi-clock-fill text-sm"></i>
                </div>
            </div>

            <!-- Header Actions -->
            <div class="flex items-center gap-4">
                <div class="relative group">
                    <button class="flex items-center gap-3 hover:bg-slate-100 p-2 rounded-xl transition-colors">
                        <div class="hidden sm:block text-right">
                            <div class="text-slate-900 font-bold text-sm leading-none">{{ auth()->user()->name }}</div>
                            <div class="text-slate-400 text-[10px] font-bold uppercase mt-1">Terverifikasi</div>
                        </div>
                        <i class="bi bi-chevron-down text-xs text-slate-400 group-hover:text-slate-600"></i>
                    </button>
                    <!-- Simple Dropdown -->
                    <div class="absolute right-0 mt-2 w-48 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform origin-top-right">
                        <a href="{{ route('profile') }}" class="flex items-center gap-2 px-4 py-2 text-slate-700 font-bold text-sm hover:bg-slate-50 transition-colors">
                            <i class="bi bi-person text-lg text-slate-400"></i> Profil Saya
                        </a>
                        <div class="my-1 border-t border-slate-50"></div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="w-full text-left px-4 py-2 text-brand-primary font-bold text-sm hover:bg-brand-primary/5 flex items-center gap-2 transition-colors">
                                <i class="bi bi-power text-lg"></i> Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="p-6 lg:p-10 flex-1">
            <!-- Global Flash Messages (Toast Style) -->
            @if(session('success'))
                <div class="fixed top-24 right-6 lg:right-10 z-[2000] animate-in fade-in slide-in-from-right-10 duration-500" id="flash-message">
                    <div class="bg-brand-secondary text-brand-primary px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-3 border border-brand-secondary">
                        <i class="bi bi-check-circle-fill text-xl"></i>
                        <span class="font-bold text-sm">{{ session('success') }}</span>
                        <button onclick="document.getElementById('flash-message').remove()" class="ml-4 opacity-50 hover:opacity-100"><i class="bi bi-x-lg"></i></button>
                    </div>
                </div>
                <script>setTimeout(() => document.getElementById('flash-message')?.remove(), 5000);</script>
            @endif

            @if(session('error'))
                <div class="fixed top-24 right-6 lg:right-10 z-[2000] animate-in fade-in slide-in-from-right-10 duration-500" id="flash-error">
                    <div class="bg-brand-primary text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-3 border border-brand-primary">
                        <i class="bi bi-exclamation-triangle-fill text-xl"></i>
                        <span class="font-bold text-sm">{{ session('error') }}</span>
                        <button onclick="document.getElementById('flash-error').remove()" class="ml-4 opacity-50 hover:opacity-100"><i class="bi bi-x-lg"></i></button>
                    </div>
                </div>
                <script>setTimeout(() => document.getElementById('flash-error')?.remove(), 5000);</script>
            @endif

            <div class="mb-10 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                <div>
                    <h1 class="text-slate-900 font-extrabold text-3xl lg:text-4xl tracking-tight">@yield('page-title', 'Dashboard')</h1>
                    <p class="text-slate-500 font-medium mt-1">@yield('page-subtitle', 'Pantau performa bisnis Anda.')</p>
                </div>
                <div class="flex items-center gap-3">
                    @yield('topbar-actions')
                </div>
            </div>

            <div class="animate-in fade-in slide-in-from-bottom-4 duration-500">
                @yield('content')
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const isHidden = sidebar.classList.contains('-translate-x-full');
            
            if (isHidden) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
                setTimeout(() => overlay.classList.add('opacity-100'), 10);
            } else {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.remove('opacity-100');
                setTimeout(() => overlay.classList.add('hidden'), 300);
            }
        }

        // Global Toast System
        function showToast(message, type = 'success') {
            const id = 'toast-' + Math.random().toString(36).substr(2, 9);
            const bgColor = type === 'success' ? 'var(--brand-secondary, #FFD60A)' : 'var(--brand-primary, #1E1E1E)';
            const textColor = type === 'success' ? 'var(--brand-primary, #1E1E1E)' : '#FFFFFF';
            const icon = type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill';
            
            const html = `
                <div id="${id}" class="fixed top-24 right-6 lg:right-10 z-[2000] animate-in fade-in slide-in-from-right-10 duration-500">
                    <div style="background-color: ${bgColor}; color: ${textColor}" class="px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-3">
                        <i class="bi ${icon} text-xl"></i>
                        <span class="font-bold text-sm">${message}</span>
                        <button onclick="document.getElementById('${id}').remove()" class="ml-4 opacity-50 hover:opacity-100"><i class="bi bi-x-lg"></i></button>
                    </div>
                </div>
            `;
            document.body.insertAdjacentHTML('beforeend', html);
            setTimeout(() => document.getElementById(id)?.remove(), 5000);
        }
        // Realtime WIB Clock
        function updateClock() {
            const now = new Date();
            const options = { 
                timeZone: 'Asia/Jakarta', 
                hour: '2-digit', 
                minute: '2-digit', 
                second: '2-digit', 
                hour12: false 
            };
            const dateOptions = {
                timeZone: 'Asia/Jakarta',
                weekday: 'long',
                day: 'numeric',
                month: 'short',
                year: 'numeric'
            };
            
            const timeString = new Intl.DateTimeFormat('en-GB', options).format(now);
            const dateString = new Intl.DateTimeFormat('en-GB', dateOptions).format(now);
            
            const clockElement = document.getElementById('realtime-clock');
            const dateElement = document.getElementById('realtime-date');
            
            if (clockElement) clockElement.textContent = timeString;
            if (dateElement) dateElement.textContent = 'WIB — ' + dateString.toUpperCase();
        }

        setInterval(updateClock, 1000);
        updateClock();
    </script>
    @stack('scripts')
</body>
</html>
