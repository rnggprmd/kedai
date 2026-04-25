<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KedaiPos — Revolusi Manajemen Restoran Modern</title>
    
    <!-- Vite Assets (Tailwind 4.0) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Icons & Fonts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .gradient-text {
            background: linear-gradient(135deg, #C1121F 0%, #9D4EDD 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .hero-bg {
            background: radial-gradient(circle at top right, rgba(193, 18, 31, 0.08), transparent 40%),
                        radial-gradient(circle at bottom left, rgba(157, 78, 221, 0.05), transparent 40%);
        }
    </style>
</head>
<body class="bg-white text-slate-900 antialiased overflow-x-hidden hero-bg">

    <!-- Premium Navbar -->
    <nav class="sticky top-0 z-[1000] bg-white/80 backdrop-blur-xl border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-brand-primary rounded-xl flex items-center justify-center text-white shadow-lg shadow-brand-primary/30">
                        <i class="bi bi-lightning-charge-fill text-xl"></i>
                    </div>
                    <span class="text-slate-900 font-extrabold text-2xl tracking-tight">KedaiPos</span>
                </div>
                <div class="hidden md:flex items-center gap-10">
                    <a href="#features" class="text-sm font-bold text-slate-500 hover:text-brand-primary transition-colors">Fitur</a>
                    <a href="#about" class="text-sm font-bold text-slate-500 hover:text-brand-primary transition-colors">Tentang Kami</a>
                    @auth
                        <a href="{{ auth()->user()->role == 'admin' ? route('admin.dashboard') : route('kasir.dashboard') }}" class="bg-slate-900 text-white px-6 py-3 rounded-2xl font-bold text-sm hover:bg-slate-800 transition-all shadow-xl shadow-slate-200">
                            Ke Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-bold text-slate-900 hover:text-brand-primary transition-colors">Masuk</a>
                        <a href="{{ route('login') }}" class="bg-brand-primary text-white px-8 py-3.5 rounded-2xl font-extrabold text-sm hover:bg-slate-900 transition-all shadow-xl shadow-brand-primary/20">
                            Coba Gratis
                        </a>
                    @endauth
                </div>
                <button class="md:hidden text-slate-900">
                    <i class="bi bi-list text-3xl"></i>
                </button>
            </div>
        </div>
    </nav>

    <!-- Ultra-Modern Hero Section -->
    <main class="relative pt-20 pb-32 overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-20 items-center">
                <div class="relative z-10">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-brand-primary/10 border border-brand-primary/20 text-brand-primary text-xs font-extrabold uppercase tracking-widest mb-8 animate-bounce">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand-primary opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-brand-primary"></span>
                        </span>
                        Baru: Antarmuka Tailwind 4.0
                    </div>
                    <h1 class="text-6xl lg:text-7xl font-extrabold tracking-tight text-slate-900 leading-[1.1] mb-8">
                        Kelola Kedai Jadi <span class="gradient-text">Lebih Cerdas.</span>
                    </h1>
                    <p class="text-slate-500 text-xl font-medium leading-relaxed mb-12 max-w-xl">
                        Sistem POS tercanggih dengan integrasi QR Order, manajemen stok real-time, dan dashboard analitik yang akan memukau bisnis Anda.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-5">
                        <a href="{{ route('login') }}" class="bg-brand-primary text-white px-10 py-5 rounded-[2rem] font-extrabold text-lg hover:bg-slate-900 transition-all shadow-2xl shadow-brand-primary/30 flex items-center justify-center gap-3">
                            Mulai Sekarang <i class="bi bi-arrow-right"></i>
                        </a>
                        <a href="#demo" class="bg-white text-slate-900 border border-slate-200 px-10 py-5 rounded-[2rem] font-extrabold text-lg hover:bg-slate-50 transition-all flex items-center justify-center gap-3">
                            Lihat Demo
                        </a>
                    </div>
                    
                    <div class="mt-16 flex items-center gap-6">
                        <div class="flex -space-x-3">
                            <img src="https://ui-avatars.com/api/?name=User+1&background=C1121F&color=fff" class="w-12 h-12 rounded-full border-4 border-white shadow-sm">
                            <img src="https://ui-avatars.com/api/?name=User+2&background=00BFA6&color=fff" class="w-12 h-12 rounded-full border-4 border-white shadow-sm">
                            <img src="https://ui-avatars.com/api/?name=User+3&background=9D4EDD&color=fff" class="w-12 h-12 rounded-full border-4 border-white shadow-sm">
                        </div>
                        <div>
                            <div class="text-slate-900 font-extrabold text-lg">500+ Kedai</div>
                            <div class="text-slate-400 text-xs font-bold uppercase tracking-widest">Sudah Bergabung</div>
                        </div>
                    </div>
                </div>

                <!-- Floating UI Elements -->
                <div class="relative">
                    <div class="bg-white rounded-[3rem] p-8 shadow-[0_50px_100px_-20px_rgba(0,0,0,0.1)] border border-slate-100 relative z-20">
                        <div class="flex items-center justify-between mb-10">
                            <div>
                                <h4 class="text-slate-900 font-extrabold text-xl mb-1">Total Penjualan</h4>
                                <p class="text-slate-400 text-xs font-bold">Laporan Real-time</p>
                            </div>
                            <div class="w-12 h-12 bg-brand-primary/10 text-brand-primary rounded-2xl flex items-center justify-center">
                                <i class="bi bi-graph-up-arrow text-xl"></i>
                            </div>
                        </div>
                        <div class="text-slate-900 text-5xl font-extrabold tracking-tight mb-10">Rp 12.8M</div>
                        <div class="space-y-6">
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500 font-bold text-sm">Target Bulanan</span>
                                <span class="text-brand-primary font-extrabold text-sm">85%</span>
                            </div>
                            <div class="h-3 bg-slate-50 rounded-full overflow-hidden">
                                <div class="h-full bg-brand-primary w-[85%] rounded-full shadow-[0_0_15px_#C1121F]"></div>
                            </div>
                        </div>
                    </div>
                    <!-- Decorative Ornaments -->
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-brand-primary rounded-full blur-[80px] opacity-20"></div>
                    <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-pink-600 rounded-full blur-[80px] opacity-10"></div>
                </div>
            </div>
        </div>
    </main>

    <!-- High-Fidelity Features Section -->
    <section id="features" class="py-32 bg-slate-50">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 text-center mb-20">
            <h2 class="text-brand-primary font-extrabold text-sm uppercase tracking-[0.3em] mb-4">Fitur Unggulan</h2>
            <h3 class="text-4xl lg:text-5xl font-extrabold text-slate-900 tracking-tight">Eksosistem Lengkap <br>Untuk Bisnis Anda</h3>
        </div>
        <div class="max-w-7xl mx-auto px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-10">
            <!-- Feature 1 -->
            <div class="bg-white p-10 rounded-[2.5rem] border border-slate-200 hover:shadow-2xl transition-all group">
                <div class="w-16 h-16 bg-brand-primary/10 text-brand-primary rounded-3xl flex items-center justify-center mb-8 group-hover:bg-brand-primary group-hover:text-white transition-all">
                    <i class="bi bi-qr-code-scan text-3xl"></i>
                </div>
                <h4 class="text-slate-900 font-extrabold text-xl mb-4">Pemesanan Meja QR</h4>
                <p class="text-slate-500 leading-relaxed font-medium">Pelanggan pesan langsung dari meja tanpa harus mengunduh aplikasi. Cukup scan, pilih, dan bayar.</p>
            </div>
            <!-- Feature 2 -->
            <div class="bg-white p-10 rounded-[2.5rem] border border-slate-200 hover:shadow-2xl transition-all group">
                <div class="w-16 h-16 bg-slate-50 text-slate-600 rounded-3xl flex items-center justify-center mb-8 group-hover:bg-slate-600 group-hover:text-white transition-all">
                    <i class="bi bi-shield-lock text-3xl"></i>
                </div>
                <h4 class="text-slate-900 font-extrabold text-xl mb-4">Keamanan Multi-Peran</h4>
                <p class="text-slate-500 leading-relaxed font-medium">Akses terproteksi khusus untuk Admin, Kasir, dan Pelanggan dengan sistem otentikasi tingkat tinggi.</p>
            </div>
            <!-- Feature 3 -->
            <div class="bg-white p-10 rounded-[2.5rem] border border-slate-200 hover:shadow-2xl transition-all group">
                <div class="w-16 h-16 bg-brand-secondary/10 text-brand-secondary rounded-3xl flex items-center justify-center mb-8 group-hover:bg-brand-secondary group-hover:text-white transition-all">
                    <i class="bi bi-pie-chart text-3xl"></i>
                </div>
                <h4 class="text-slate-900 font-extrabold text-xl mb-4">Wawasan Cerdas</h4>
                <p class="text-slate-500 leading-relaxed font-medium">Analisis penjualan otomatis yang membantu Anda memantau performa menu dan pendapatan setiap saat.</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-16 border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-8">
            <div class="flex items-center gap-3 grayscale opacity-50">
                <div class="w-8 h-8 bg-slate-900 rounded-lg flex items-center justify-center text-white">
                    <i class="bi bi-lightning-charge-fill"></i>
                </div>
                <span class="text-slate-900 font-extrabold text-xl tracking-tight">KedaiPos</span>
            </div>
            <p class="text-slate-400 text-sm font-bold">&copy; 2026 KedaiPos. Developed with Passion by Antigravity.</p>
            <div class="flex gap-6 text-slate-400">
                <a href="#" class="hover:text-brand-primary transition-colors"><i class="bi bi-twitter-x"></i></a>
                <a href="#" class="hover:text-brand-primary transition-colors"><i class="bi bi-instagram"></i></a>
                <a href="#" class="hover:text-brand-primary transition-colors"><i class="bi bi-linkedin"></i></a>
            </div>
        </div>
    </footer>

</body>
</html>
