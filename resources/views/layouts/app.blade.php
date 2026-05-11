<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — KedaiPos Tailwind</title>
    <link rel="icon" type="image/png" href="{{ asset('images/kedai wasis.png') }}">
    
    <!-- Vite Assets (Tailwind 4.0) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Icons & Fonts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Outfit', sans-serif; }
        html { scroll-behavior: smooth; }
        
        /* Global Premium Scrollbar (Browser & Containers) */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { 
            background: rgba(30, 30, 30, 0.15); 
            border-radius: 20px; 
            border: 2px solid transparent; 
            background-clip: content-box; 
        }
        ::-webkit-scrollbar-thumb:hover { 
            background: rgba(30, 30, 30, 0.3); 
        }

        /* Sidebar Navigation (Force Dark Contrast) */
        .custom-scrollbar::-webkit-scrollbar-thumb { 
            background: rgba(255, 255, 255, 0.2); 
            border-radius: 20px; 
            border: 2px solid transparent; 
            background-clip: content-box; 
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { 
            background: rgba(255, 255, 255, 0.4); 
        }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-900 antialiased overflow-x-hidden">

    <!-- Mobile Sidebar Overlay -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-[1050] hidden transition-opacity duration-300 opacity-0" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <aside id="sidebar" class="fixed top-0 left-0 bottom-0 w-[280px] z-[1100] flex flex-col transition-transform duration-300 ease-in-out -translate-x-full lg:translate-x-0 bg-[#0a001a] border-right border-white/5">
        <!-- Mobile Close Button -->
        <button class="lg:hidden absolute top-8 right-6 text-white/40 hover:text-white transition-colors" onclick="toggleSidebar()">
            <i class="bi bi-x-lg text-xl"></i>
        </button>

        <!-- Logo Section -->
        <div class="h-40 flex items-center justify-center px-8 mb-4">
            <a href="{{ auth()->user()->role == 'admin' ? route('admin.dashboard') : route('kasir.dashboard') }}" class="group">
                <div class="w-28 h-28 bg-white rounded-[2rem] flex items-center justify-center shadow-2xl group-hover:scale-105 transition-transform overflow-hidden p-3.5">
                    <img src="{{ asset('images/kedai wasis.png') }}" alt="Logo" class="w-full h-full object-contain">
                </div>
            </a>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-1 overflow-y-auto custom-scrollbar px-4 py-2">
            @yield('sidebar-nav')
        </nav>

        <!-- User Profile Bottom -->
        <div class="p-4 mt-auto border-t border-white/5 relative group">
            <button class="w-full flex items-center justify-between p-3 rounded-xl hover:bg-white/5 transition-all duration-300 group">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="relative">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=3C096C&color=fff" class="w-9 h-9 rounded-lg border border-white/10 shadow-sm">
                        <div class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 bg-green-500 border-2 border-[#0a001a] rounded-full"></div>
                    </div>
                    <div class="text-left min-w-0">
                        <div class="text-white font-bold text-xs truncate leading-tight">{{ auth()->user()->name }}</div>
                        <div class="text-white/30 text-[9px] font-bold uppercase tracking-widest mt-1">{{ auth()->user()->role == 'admin' ? 'Administrator' : 'Kasir' }}</div>
                    </div>
                </div>
                <i class="bi bi-three-dots-vertical text-white/20 group-hover:text-white transition-colors text-xs"></i>
            </button>

            <!-- Sidebar Profile Dropdown (Popup upwards) -->
            <div class="absolute bottom-full left-4 right-4 mb-2 bg-[#1a1a2e] border border-white/10 rounded-xl shadow-2xl py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform origin-bottom translate-y-1 group-hover:translate-y-0 z-[2000]">
                <div class="px-4 py-1.5 mb-1">
                    <div class="text-white/20 text-[8px] font-black uppercase tracking-[0.2em]">Menu Akun</div>
                </div>
                <a href="{{ route('profile') }}" class="flex items-center gap-3 px-4 py-2.5 text-white/60 font-bold text-xs hover:bg-white/5 hover:text-white transition-colors">
                    <i class="bi bi-person-circle opacity-50"></i>
                    <span>Profil Saya</span>
                </a>
                <div class="mx-4 border-t border-white/5 my-1"></div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="w-full flex items-center gap-3 px-4 py-2.5 text-red-400/80 font-bold text-xs hover:bg-red-500/10 transition-colors">
                        <i class="bi bi-box-arrow-right opacity-50"></i>
                        <span>Keluar Sesi</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="lg:ml-[280px] min-h-screen flex flex-col transition-all duration-300">
        <!-- Header -->
        <header class="sticky top-0 z-[1000] bg-white/80 backdrop-blur-xl h-20 border-b border-slate-200 flex items-center justify-between px-6 lg:px-10">
            <div class="flex items-center gap-4">
                <button class="lg:hidden text-slate-600 hover:text-slate-900" onclick="toggleSidebar()">
                    <i class="bi bi-list text-3xl"></i>
                </button>
                <div class="flex flex-col">
                    <h1 class="text-slate-900 font-extrabold text-xl leading-none tracking-tight">@yield('page-title', 'Dashboard')</h1>
                    <p class="text-slate-400 font-medium text-[11px] mt-1 hidden sm:block">@yield('page-subtitle', 'Pantau performa bisnis Anda.')</p>
                </div>
            </div>

            <!-- Topbar Actions -->
            <div class="flex items-center gap-2 lg:gap-4">
                @if(trim($__env->yieldContent('topbar-actions')))
                <div class="flex items-center gap-2 lg:gap-3">
                    @yield('topbar-actions')
                </div>
                @endif
            </div>

            <div class="flex items-center gap-3 lg:gap-6">
                <!-- Minimalist Realtime WIB Clock -->
                <div class="flex flex-col items-end shrink-0">
                    <div id="realtime-clock" class="text-slate-900 font-black text-[10px] lg:text-sm tracking-wider leading-none mb-1">00:00:00</div>
                    <div id="realtime-date" class="hidden md:block text-slate-400 text-[9px] font-bold uppercase tracking-[0.1em]">WIB — LOADING</div>
                    <div class="md:hidden text-slate-400 text-[8px] font-bold">WIB</div>
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
