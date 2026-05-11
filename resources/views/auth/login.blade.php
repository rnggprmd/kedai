<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>KedaiPos - Login</title>
    <link rel="icon" type="image/png" href="{{ asset('images/kedai wasis.png') }}">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            "colors": {
                    "primary": "#9D4EDD",
                    "primary-dark": "#3C096C",
                    "on-primary": "#ffffff",
                    "secondary": "#64748b",
                    "background": "#f8fafc",
                    "on-background": "#0f172a",
                    "surface": "#ffffff",
                    "surface-container": "#f1f5f9",
                    "surface-container-highest": "#e2e8f0",
                    "surface-container-lowest": "#ffffff",
                    "outline": "#94a3b8",
                    "outline-variant": "#e2e8f0",
                    "error": "#1E1E1E",
                    "on-surface-variant": "#475569",
                    "accent-yellow": "#FFD60A",
            },
            "borderRadius": {
                    "DEFAULT": "0.25rem",
                    "lg": "0.5rem",
                    "xl": "0.75rem",
                    "2xl": "1rem",
                    "3xl": "1.5rem",
                    "full": "9999px"
            },
            "fontFamily": {
                    "h": ["Outfit", "sans-serif"],
                    "body": ["Outfit", "sans-serif"],
            }
          },
        },
      }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .auth-card-shadow {
            box-shadow: 0 20px 50px -12px rgba(60, 9, 108, 0.12);
        }
        .purple-gradient {
            background: linear-gradient(180deg, #240046 0%, #3C096C 100%);
        }
        .fade-in {
            animation: fadeIn 0.8s ease-out forwards;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .stagger-1 { animation-delay: 0.1s; }
        .stagger-2 { animation-delay: 0.2s; }
        .stagger-3 { animation-delay: 0.3s; }
    </style>
</head>
<body class="bg-background text-on-background h-screen font-body overflow-hidden">
    <main class="flex h-screen w-full overflow-hidden">
        <!-- Left Column: Visual Hero (Desktop Only) -->
        <section class="hidden lg:block lg:w-1/2 relative overflow-hidden">
            <div class="absolute inset-0 purple-gradient opacity-60 z-10"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-[#240046] via-transparent to-transparent z-10"></div>
            <img src="{{ asset('images/background wasis.jpeg') }}" alt="KedaiPos Experience" class="w-full h-full object-cover scale-110"/>
            
            <!-- Branding Overlay -->
            <div class="absolute bottom-16 left-16 z-20 max-w-lg fade-in">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center shadow-2xl p-2.5">
                        <img src="{{ asset('images/kedai wasis.png') }}" alt="Logo" class="w-full h-full object-contain"/>
                    </div>
                    <h2 class="text-white font-h text-4xl font-black tracking-tighter">Kedai Wasis</h2>
                </div>
                <h2 class="font-h text-5xl text-white mb-6 leading-[1.1] font-black">Kelola Kedai <br/><span class="text-accent-yellow">Jadi Lebih Mudah</span>.</h2>
                <p class="text-xl text-white/70 leading-relaxed font-medium">Sistem cerdas untuk operasional yang lebih cepat dan pelayanan pelanggan yang lebih prima di Kedai Wasis.</p>
            </div>
        </section>

        <!-- Right Column: Login Form -->
        <section class="w-full lg:w-1/2 bg-slate-50 flex flex-col items-center justify-center p-6 md:p-12 relative">
            <!-- Decorative elements for branding consistency -->
            <div class="absolute top-0 right-0 w-80 h-80 bg-primary/5 rounded-full blur-[120px] -mr-40 -mt-40"></div>
            <div class="absolute bottom-0 left-0 w-80 h-80 bg-primary/5 rounded-full blur-[120px] -ml-40 -mb-40"></div>

            <div class="w-full max-w-[460px] bg-white p-8 md:p-12 rounded-[2.5rem] shadow-2xl auth-card-shadow border border-slate-100 relative z-10 fade-in">
                <!-- Header -->
                <div class="mb-10 text-center lg:text-left stagger-1">
                    <div class="lg:hidden flex items-center justify-center gap-3 mb-10">
                        <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center shadow-xl p-2.5">
                            <img src="{{ asset('images/kedai wasis.png') }}" alt="Logo" class="w-full h-full object-contain"/>
                        </div>
                        <h1 class="font-h text-3xl text-on-background font-black tracking-tight">KedaiPos</h1>
                    </div>
                    <h2 class="font-h text-3xl text-on-background mb-3 font-black tracking-tight">Selamat Datang</h2>
                    <p class="text-secondary font-semibold text-base">Masuk ke dashboard manajemen Anda.</p>
                </div>

                <!-- Error Alert -->
                @if($errors->any())
                <div class="mb-8 p-4 bg-error text-white rounded-2xl flex items-start gap-3 shadow-lg">
                    <span class="material-symbols-outlined text-accent-yellow text-xl">warning</span>
                    <div class="text-xs font-bold">{{ $errors->first() }}</div>
                </div>
                @endif

                <!-- Login Form -->
                <form action="{{ route('login.attempt') }}" method="POST" class="space-y-6 stagger-2" id="loginForm">
                    @csrf
                    <!-- Email Field -->
                    <div class="space-y-2">
                        <label class="font-bold text-[10px] uppercase tracking-[0.2em] text-secondary ml-1" for="email">Alamat Email</label>
                        <div class="relative group">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline group-focus-within:text-primary transition-colors text-[18px]">alternate_email</span>
                            <input type="email" name="email" id="email" 
                                class="w-full h-14 pl-11 pr-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-0 focus:border-primary/30 focus:bg-white transition-all outline-none font-bold text-sm text-on-background placeholder:text-outline/40" 
                                placeholder="nama@email.com" value="{{ old('email') }}" required autofocus/>
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div class="space-y-2">
                        <div class="flex justify-between items-center px-1">
                            <label class="font-bold text-[10px] uppercase tracking-[0.2em] text-secondary" for="password">Kata Sandi</label>
                            <a class="font-bold text-[10px] uppercase tracking-[0.2em] text-primary hover:text-primary-dark transition-all" href="#">Lupa?</a>
                        </div>
                        <div class="relative group">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline group-focus-within:text-primary transition-colors text-[18px]">lock_person</span>
                            <input type="password" name="password" id="password" 
                                class="w-full h-14 pl-11 pr-12 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-0 focus:border-primary/30 focus:bg-white transition-all outline-none font-bold text-sm text-on-background placeholder:text-outline/40" 
                                placeholder="••••••••" required/>
                            <button class="absolute right-4 top-1/2 -translate-y-1/2 text-outline hover:text-on-surface transition-colors" type="button" onclick="togglePassword()">
                                <span class="material-symbols-outlined text-[18px]" id="eyeIcon">visibility</span>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center gap-3 px-1 pt-1">
                        <input type="checkbox" name="remember" id="remember" class="w-5 h-5 rounded border-2 border-slate-200 text-primary focus:ring-primary/20 transition-all cursor-pointer bg-slate-50"/>
                        <label for="remember" class="text-xs font-bold text-secondary cursor-pointer select-none">Ingat perangkat ini</label>
                    </div>

                    <!-- Primary Action -->
                    <button id="submitBtn" class="w-full h-[56px] bg-primary text-on-primary font-h text-base font-black rounded-2xl shadow-xl shadow-primary/20 active:scale-[0.98] transition-all duration-200 hover:bg-primary-dark mt-2 flex items-center justify-center gap-3" type="submit">
                        <span id="btnText">Masuk Dashboard</span>
                        <span class="material-symbols-outlined text-xl" id="btnIcon">login</span>
                        <div id="btnSpinner" class="hidden w-5 h-5 border-4 border-white/30 border-t-white rounded-full animate-spin"></div>
                    </button>
                </form>

                <!-- Footer Link -->
                <p class="mt-8 text-center text-secondary font-bold text-sm stagger-3">
                    Belum punya akun? 
                    <a class="text-primary font-black hover:underline ml-1" href="https://wa.me/6281234567890?text=Halo%20Admin%20Kedai%20Wasis,%20saya%20ingin%20meminta%20akses%20akun%20KedaiPos.">Hubungi Admin</a>
                </p>
            </div>
        </section>
    </main>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('eyeIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerText = 'visibility_off';
            } else {
                input.type = 'password';
                icon.innerText = 'visibility';
            }
        }

        document.getElementById('loginForm').addEventListener('submit', function() {
            const btn = document.getElementById('submitBtn');
            const text = document.getElementById('btnText');
            const icon = document.getElementById('btnIcon');
            const spinner = document.getElementById('btnSpinner');
            
            btn.classList.add('opacity-80', 'cursor-not-allowed');
            text.innerText = 'Memverifikasi...';
            icon.classList.add('hidden');
            spinner.classList.remove('hidden');
        });
    </script>
</body>
</html>

