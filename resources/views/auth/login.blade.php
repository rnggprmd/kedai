<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — KedaiPos Tailwind</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-6 relative overflow-hidden">
    <!-- Background Ornaments -->
    <div class="absolute top-0 right-0 w-1/2 h-full bg-indigo-600/5 -skew-x-12 transform origin-right animate-pulse"></div>
    <div class="absolute -top-24 -left-24 w-96 h-96 bg-indigo-600/10 rounded-full blur-[100px] animate-pulse"></div>
    <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-rose-600/10 rounded-full blur-[100px] animate-pulse" style="animation-delay: 2s;"></div>

    <div class="w-full max-w-md relative z-10 animate-in fade-in zoom-in duration-700">
        <!-- Logo -->
        <div class="flex flex-col items-center mb-10">
            <div class="w-20 h-20 bg-indigo-600 rounded-[2rem] flex items-center justify-center text-white shadow-2xl shadow-indigo-200 mb-6 group hover:scale-110 transition-transform duration-500 cursor-pointer">
                <i class="bi bi-lightning-charge-fill text-4xl"></i>
            </div>
            <h1 class="text-slate-900 text-3xl font-extrabold tracking-tight">Welcome to KedaiPos</h1>
            <p class="text-slate-400 font-medium mt-2">Sign in to manage your restaurant</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white p-10 rounded-[2.5rem] border border-slate-200 shadow-2xl shadow-slate-200/50">
            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf
                
                @if($errors->any())
                    <div class="bg-rose-50 border border-rose-100 text-rose-600 p-4 rounded-2xl text-xs font-bold uppercase tracking-widest animate-pulse">
                        {{ $errors->first() }}
                    </div>
                @endif

                <div>
                    <label class="block text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-3 ml-1">Email Address</label>
                    <div class="relative group">
                        <i class="bi bi-envelope absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-indigo-600 transition-colors"></i>
                        <input type="email" name="email" class="w-full bg-slate-50 border-none rounded-2xl pl-12 pr-5 py-4 font-bold text-slate-900 focus:ring-2 focus:ring-indigo-600 transition-all placeholder:text-slate-300" placeholder="admin@kedaipos.com" value="{{ old('email') }}" required autofocus>
                    </div>
                </div>

                <div>
                    <label class="block text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-3 ml-1">Password</label>
                    <div class="relative group">
                        <i class="bi bi-shield-lock absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-indigo-600 transition-colors"></i>
                        <input type="password" name="password" class="w-full bg-slate-50 border-none rounded-2xl pl-12 pr-5 py-4 font-bold text-slate-900 focus:ring-2 focus:ring-indigo-600 transition-all placeholder:text-slate-300" placeholder="••••••••" required>
                    </div>
                </div>

                <div class="flex items-center justify-between px-1">
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded-lg border-slate-200 text-indigo-600 focus:ring-indigo-600 bg-slate-50">
                        <span class="text-xs font-bold text-slate-400 group-hover:text-slate-600 transition-colors uppercase tracking-widest">Remember me</span>
                    </label>
                    <a href="#" class="text-xs font-bold text-indigo-600 hover:underline uppercase tracking-widest">Forgot?</a>
                </div>

                <button type="submit" class="w-full bg-indigo-600 text-white font-extrabold py-5 rounded-2xl hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-200 flex items-center justify-center gap-2 group mt-8">
                    Sign In to Dashboard <i class="bi bi-arrow-right group-hover:translate-x-1 transition-transform"></i>
                </button>
            </form>
        </div>

        <p class="text-center text-slate-400 text-xs font-bold uppercase tracking-widest mt-10">
            © {{ date('Y') }} KedaiPos Ecosystem • V1.0
        </p>
    </div>
</body>
</html>
