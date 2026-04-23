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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        /* Smooth scroll for the whole page */
        html { scroll-behavior: smooth; }
        /* Custom scrollbar for sidebar */
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.1); border-radius: 10px; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased overflow-x-hidden">

    <!-- Mobile Sidebar Overlay -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-[1050] hidden transition-opacity duration-300 opacity-0" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <aside id="sidebar" class="fixed top-0 left-0 bottom-0 w-[260px] bg-slate-900 z-[1100] flex flex-col p-6 transition-transform duration-300 ease-in-out -translate-x-full lg:translate-x-0">
        <!-- Mobile Close Button -->
        <button class="lg:hidden absolute top-8 right-6 text-slate-500 hover:text-white transition-colors" onclick="toggleSidebar()">
            <i class="bi bi-x-lg text-xl"></i>
        </button>

        <!-- Logo -->
        <a href="{{ auth()->user()->role == 'admin' ? route('admin.dashboard') : route('kasir.dashboard') }}" class="flex items-center gap-3 mb-10 px-2 group">
            <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-indigo-500/30 group-hover:scale-110 transition-transform">
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
            <div class="flex items-center gap-3 p-3 bg-white/5 rounded-2xl">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=6366f1&color=fff" class="w-10 h-10 rounded-xl shadow-sm">
                <div class="min-w-0">
                    <div class="text-white font-bold text-sm truncate">{{ auth()->user()->name }}</div>
                    <div class="text-slate-400 text-[10px] font-bold uppercase tracking-wider">{{ auth()->user()->role }}</div>
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

            <!-- Header Actions -->
            <div class="flex items-center gap-4">
                <div class="relative group">
                    <button class="flex items-center gap-3 hover:bg-slate-100 p-2 rounded-xl transition-colors">
                        <div class="hidden sm:block text-right">
                            <div class="text-slate-900 font-bold text-sm leading-none">{{ auth()->user()->name }}</div>
                            <div class="text-slate-400 text-[10px] font-bold uppercase mt-1">Verified</div>
                        </div>
                        <i class="bi bi-chevron-down text-xs text-slate-400 group-hover:text-slate-600"></i>
                    </button>
                    <!-- Simple Dropdown -->
                    <div class="absolute right-0 mt-2 w-48 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform origin-top-right">
                        <a href="{{ route('profile') }}" class="flex items-center gap-2 px-4 py-2 text-slate-700 font-bold text-sm hover:bg-slate-50 transition-colors">
                            <i class="bi bi-person text-lg text-slate-400"></i> My Profile
                        </a>
                        <div class="my-1 border-t border-slate-50"></div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="w-full text-left px-4 py-2 text-rose-600 font-bold text-sm hover:bg-rose-50 flex items-center gap-2 transition-colors">
                                <i class="bi bi-power text-lg"></i> Sign Out
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
                    <div class="bg-emerald-600 text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-3 border border-emerald-500">
                        <i class="bi bi-check-circle-fill text-xl"></i>
                        <span class="font-bold text-sm">{{ session('success') }}</span>
                        <button onclick="document.getElementById('flash-message').remove()" class="ml-4 opacity-50 hover:opacity-100"><i class="bi bi-x-lg"></i></button>
                    </div>
                </div>
                <script>setTimeout(() => document.getElementById('flash-message')?.remove(), 5000);</script>
            @endif

            @if(session('error'))
                <div class="fixed top-24 right-6 lg:right-10 z-[2000] animate-in fade-in slide-in-from-right-10 duration-500" id="flash-error">
                    <div class="bg-rose-600 text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-3 border border-rose-500">
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
                    <p class="text-slate-500 font-medium mt-1">@yield('page-subtitle', 'Monitoring your business performance.')</p>
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
            const color = type === 'success' ? 'emerald' : 'rose';
            const icon = type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill';
            
            const html = `
                <div id="${id}" class="fixed top-24 right-6 lg:right-10 z-[2000] animate-in fade-in slide-in-from-right-10 duration-500">
                    <div class="bg-${color}-600 text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-3 border border-${color}-500">
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
