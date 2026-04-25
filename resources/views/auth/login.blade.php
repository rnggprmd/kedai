<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — KedaiPos</title>
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
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-6 relative overflow-hidden">

    {{-- Background --}}
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-0 right-0 w-1/2 h-full bg-gradient-to-bl from-brand-accent/5 to-transparent"></div>
        <div class="absolute -top-32 -left-32 w-[500px] h-[500px] bg-brand-primary/5 rounded-full blur-[120px]"></div>
        <div class="absolute -bottom-32 -right-32 w-[500px] h-[500px] bg-brand-accent/10 rounded-full blur-[120px]"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-px h-full bg-gradient-to-b from-transparent via-slate-200/60 to-transparent opacity-50"></div>
    </div>

    <div class="w-full max-w-md relative z-10">
        {{-- Logo & Brand --}}
        <div class="flex flex-col items-center mb-10">
            <div class="relative mb-6">
                <div class="absolute inset-0 bg-brand-accent rounded-[2rem] blur-2xl opacity-30 scale-125"></div>
                <div class="relative w-20 h-20 bg-brand-accent rounded-[2rem] flex items-center justify-center text-white shadow-2xl shadow-brand-accent/30 hover:scale-105 transition-transform duration-500 cursor-default">
                    <i class="bi bi-lightning-charge-fill text-4xl"></i>
                </div>
            </div>
            <h1 class="text-slate-900 text-3xl font-black tracking-tight">KedaiPos</h1>
            <p class="text-slate-400 font-medium mt-1 text-sm">Masuk untuk mengelola restoran Anda</p>
        </div>

        {{-- Login Card --}}
        <div class="bg-white/90 backdrop-blur-xl p-8 lg:p-10 rounded-[2.5rem] border border-slate-200/80 shadow-2xl shadow-slate-200/50">

            {{-- Error Alert --}}
            @if($errors->any())
            <div class="bg-brand-primary/5 border border-brand-primary/20 text-brand-primary p-4 rounded-2xl mb-6 flex items-start gap-3">
                <div class="w-8 h-8 bg-brand-primary/10 rounded-xl flex items-center justify-center shrink-0 mt-0.5">
                    <i class="bi bi-exclamation-triangle-fill text-brand-primary text-sm"></i>
                </div>
                <div>
                    <div class="font-extrabold text-xs uppercase tracking-widest mb-0.5">Login Gagal</div>
                    <div class="text-xs font-medium">{{ $errors->first() }}</div>
                </div>
            </div>
            @endif

            <form action="{{ route('login.attempt') }}" method="POST" class="space-y-5" id="loginForm">
                @csrf

                {{-- Email --}}
                <div>
                    <label class="block text-slate-500 text-[10px] font-bold uppercase tracking-widest mb-2.5 ml-1">Alamat Email</label>
                    <div class="relative group">
                        <i class="bi bi-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-brand-primary transition-colors text-sm"></i>
                        <input type="email" name="email"
                            class="w-full bg-slate-50 border border-slate-200 rounded-2xl pl-11 pr-5 py-4 font-bold text-slate-900 text-sm focus:ring-2 focus:ring-brand-primary focus:border-brand-primary transition-all placeholder:text-slate-300 @error('email') border-brand-primary/30 bg-brand-primary/5 @enderror"
                            placeholder="Masukkan Username"
                            value="{{ old('email') }}"
                            required autofocus>
                    </div>
                </div>

                {{-- Password --}}
                <div>
                    <label class="block text-slate-500 text-[10px] font-bold uppercase tracking-widest mb-2.5 ml-1">Password</label>
                    <div class="relative group">
                        <i class="bi bi-shield-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-brand-primary transition-colors text-sm"></i>
                        <input type="password" name="password" id="passwordInput"
                            class="w-full bg-slate-50 border border-slate-200 rounded-2xl pl-11 pr-12 py-4 font-bold text-slate-900 text-sm focus:ring-2 focus:ring-brand-primary focus:border-brand-primary transition-all placeholder:text-slate-300"
                            placeholder="••••••••"
                            required>
                        <button type="button" onclick="togglePassword()"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 hover:text-slate-600 transition-colors w-6 h-6 flex items-center justify-center"
                            tabindex="-1">
                            <i class="bi bi-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                {{-- Remember Me --}}
                <div class="flex items-center justify-between px-1 pt-1">
                    <label class="flex items-center gap-2.5 cursor-pointer group">
                        <input type="checkbox" name="remember"
                            class="w-4 h-4 rounded border-slate-200 text-brand-primary focus:ring-brand-primary bg-slate-50 cursor-pointer">
                        <span class="text-xs font-bold text-slate-400 group-hover:text-slate-600 transition-colors">Ingat saya</span>
                    </label>
                </div>

                {{-- Submit --}}
                <button type="submit" id="submitBtn"
                    class="w-full bg-brand-primary text-white font-extrabold py-4.5 rounded-2xl hover:bg-slate-900 active:scale-[0.98] transition-all shadow-xl shadow-brand-primary/20 flex items-center justify-center gap-2 group mt-2">
                    <span id="btnText">Masuk ke Dashboard</span>
                    <i class="bi bi-arrow-right group-hover:translate-x-1 transition-transform" id="btnIcon"></i>
                    <svg id="btnSpinner" class="hidden animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 12 5.373 12 12h4z"></path>
                    </svg>
                </button>
            </form>
        </div>

        {{-- Footer --}}
        <p class="text-center text-slate-400 text-[10px] font-bold uppercase tracking-widest mt-8">
            © {{ date('Y') }} KedaiPos • Sistem Manajemen Restoran
        </p>
    </div>

<script>
    function togglePassword() {
        const input = document.getElementById('passwordInput');
        const icon  = document.getElementById('eyeIcon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'bi bi-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'bi bi-eye';
        }
    }

    document.getElementById('loginForm').addEventListener('submit', function() {
        const btn     = document.getElementById('submitBtn');
        const text    = document.getElementById('btnText');
        const icon    = document.getElementById('btnIcon');
        const spinner = document.getElementById('btnSpinner');
        btn.disabled  = true;
        text.textContent = 'Memproses...';
        icon.classList.add('hidden');
        spinner.classList.remove('hidden');
    });
</script>
</body>
</html>
