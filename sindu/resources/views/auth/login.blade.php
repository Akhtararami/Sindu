<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - SINDU</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-50 via-slate-100 to-emerald-50/40 min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full space-y-6">
        <!-- Brand / Header -->
        <div class="text-center space-y-2">
            <div class="inline-flex items-center justify-center w-12 h-12 bg-emerald-600 rounded-2xl shadow-lg shadow-emerald-200 mb-2">
                <i data-lucide="activity" class="text-white w-6 h-6"></i>
            </div>
            <h1 class="text-3xl font-extrabold tracking-tight bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent">SINDU</h1>
            <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Sistem Posyandu Digital Terpadu</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white/80 backdrop-blur-md rounded-3xl p-6 sm:p-8 shadow-xl border border-slate-100/50">
            <h2 class="text-xl font-bold text-slate-800 mb-6">Masuk ke Akun Anda</h2>
            
            @if(session('success'))
                <div class="mb-4 p-3.5 bg-emerald-50 border border-emerald-100 text-emerald-700 text-xs font-bold rounded-xl flex items-center gap-2">
                    <i data-lucide="check-circle" class="w-4 h-4 shrink-0"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 p-3.5 bg-red-50 border border-red-100 text-red-700 text-xs font-semibold rounded-xl space-y-1">
                    @foreach($errors->all() as $error)
                        <div class="flex items-center gap-2">
                            <i data-lucide="alert-circle" class="w-4 h-4 shrink-0"></i>
                            <span>{{ $error }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            <form action="/login" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Alamat Email</label>
                    <div class="relative">
                        <i data-lucide="mail" class="absolute left-3.5 top-3 text-slate-400 w-4.5 h-4.5"></i>
                        <input type="email" name="email" required value="{{ old('email') }}" placeholder="contoh@posyandu.id" class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-100 focus:border-emerald-500 focus:bg-white text-sm font-semibold rounded-xl outline-none transition-all duration-300">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Password</label>
                    <div class="relative">
                        <i data-lucide="lock" class="absolute left-3.5 top-3 text-slate-400 w-4.5 h-4.5"></i>
                        <input type="password" name="password" required placeholder="••••••••" class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-100 focus:border-emerald-500 focus:bg-white text-sm font-semibold rounded-xl outline-none transition-all duration-300">
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs font-bold text-slate-500">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded text-emerald-600 focus:ring-emerald-500 border-slate-200">
                        <span>Ingat Saya</span>
                    </label>
                </div>

                <button type="submit" class="w-full flex items-center justify-center space-x-2 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-2xl shadow-md shadow-emerald-600/10 transition-all duration-300">
                    <i data-lucide="log-in" class="w-4 h-4"></i>
                    <span>Masuk Aplikasi</span>
                </button>
            </form>

            <div class="mt-6 text-center text-xs font-bold text-slate-400">
                <span>Belum memiliki akun? </span>
                <a href="/register" class="text-emerald-600 hover:underline">Daftar Sekarang</a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
        });
    </script>
</body>
</html>
