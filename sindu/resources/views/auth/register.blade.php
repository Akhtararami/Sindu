<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Akun - SINDU</title>
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

        <!-- Register Card -->
        <div class="bg-white/80 backdrop-blur-md rounded-3xl p-6 sm:p-8 shadow-xl border border-slate-100/50">
            <h2 class="text-xl font-bold text-slate-800 mb-6">Pendaftaran Akun Baru</h2>

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

            <form action="/register" method="POST" class="space-y-4" autocomplete="off">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Lengkap</label>
                    <div class="relative">
                        <i data-lucide="user" class="absolute left-3.5 top-3 text-slate-400 w-4.5 h-4.5"></i>
                        <input type="text" name="name" required value="{{ old('name') }}" autocomplete="off" placeholder="Nama Lengkap Anda" class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-100 focus:border-emerald-500 focus:bg-white text-sm font-semibold rounded-xl outline-none transition-all duration-300">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Alamat Email</label>
                    <div class="relative">
                        <i data-lucide="mail" class="absolute left-3.5 top-3 text-slate-400 w-4.5 h-4.5"></i>
                        <input type="email" name="email" required value="{{ old('email') }}" autocomplete="off" placeholder="contoh@posyandu.id" class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-100 focus:border-emerald-500 focus:bg-white text-sm font-semibold rounded-xl outline-none transition-all duration-300">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nomor HP / WhatsApp</label>
                    <div class="relative">
                        <i data-lucide="phone" class="absolute left-3.5 top-3 text-slate-400 w-4.5 h-4.5"></i>
                        <input type="text" name="phone_number" required value="{{ old('phone_number') }}" autocomplete="off" placeholder="08xxxxxxxxxx" class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-100 focus:border-emerald-500 focus:bg-white text-sm font-semibold rounded-xl outline-none transition-all duration-300">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Daftar Sebagai</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label id="role-kader-btn" onclick="selectRole('kader')" class="flex items-center justify-center p-3 bg-emerald-50 border border-emerald-500 text-emerald-700 rounded-xl cursor-pointer font-bold text-xs shadow-sm transition-all duration-300">
                            <input type="radio" name="role" value="kader" checked class="absolute opacity-0">
                            <i data-lucide="shield-check" class="w-4 h-4 mr-1 text-emerald-600"></i> Kader Posyandu
                        </label>
                        <label id="role-user-btn" onclick="selectRole('user')" class="flex items-center justify-center p-3 bg-white border border-slate-100 text-slate-500 hover:border-emerald-500 rounded-xl cursor-pointer font-bold text-xs transition-all duration-300">
                            <input type="radio" name="role" value="user" class="absolute opacity-0">
                            <i data-lucide="users" class="w-4 h-4 mr-1 text-slate-400"></i> Orang Tua / Wali
                        </label>
                    </div>
                </div>

                <!-- Address Input (Only for Orang Tua / Wali) -->
                <div id="address-container" class="hidden transition-all duration-300">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Alamat Lengkap</label>
                    <div class="relative">
                        <i data-lucide="map-pin" class="absolute left-3.5 top-3.5 text-slate-400 w-4.5 h-4.5"></i>
                        <textarea id="address-input" name="address" autocomplete="off" placeholder="Tuliskan alamat lengkap tempat tinggal Anda..." class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-100 focus:border-emerald-500 focus:bg-white text-sm font-semibold rounded-xl outline-none transition-all duration-300 min-h-[80px]"></textarea>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Password</label>
                        <div class="relative">
                            <i data-lucide="lock" class="absolute left-3.5 top-3 text-slate-400 w-4.5 h-4.5"></i>
                            <input type="password" name="password" required autocomplete="new-password" placeholder="••••••••" class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-100 focus:border-emerald-500 focus:bg-white text-sm font-semibold rounded-xl outline-none transition-all duration-300">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Konfirmasi Password</label>
                        <div class="relative">
                            <i data-lucide="lock-keyhole" class="absolute left-3.5 top-3 text-slate-400 w-4.5 h-4.5"></i>
                            <input type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-100 focus:border-emerald-500 focus:bg-white text-sm font-semibold rounded-xl outline-none transition-all duration-300">
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full flex items-center justify-center space-x-2 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-2xl shadow-md shadow-emerald-600/10 transition-all duration-300 mt-2">
                    <i data-lucide="user-plus" class="w-4 h-4"></i>
                    <span>Daftar Akun</span>
                </button>
            </form>

            <div class="mt-6 text-center text-xs font-bold text-slate-400">
                <span>Sudah memiliki akun? </span>
                <a href="/login" class="text-emerald-600 hover:underline">Masuk Di Sini</a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
            
            // Set dynamic fields initial status
            const checkedRole = document.querySelector('input[name="role"]:checked');
            if (checkedRole) {
                selectRole(checkedRole.value);
            }
        });

        function selectRole(role) {
            const kaderBtn = document.getElementById('role-kader-btn');
            const userBtn = document.getElementById('role-user-btn');
            const addressContainer = document.getElementById('address-container');
            const addressInput = document.getElementById('address-input');
            const kaderRadio = kaderBtn.querySelector('input[type="radio"]');
            const userRadio = userBtn.querySelector('input[type="radio"]');

            if (role === 'kader') {
                kaderBtn.className = "flex items-center justify-center p-3 bg-emerald-50 border border-emerald-500 text-emerald-700 rounded-xl cursor-pointer font-bold text-xs shadow-sm transition-all duration-300";
                userBtn.className = "flex items-center justify-center p-3 bg-white border border-slate-100 text-slate-500 hover:border-emerald-500 rounded-xl cursor-pointer font-bold text-xs transition-all duration-300";
                kaderRadio.checked = true;
                userRadio.checked = false;
                addressContainer.classList.add('hidden');
                addressInput.removeAttribute('required');
            } else {
                userBtn.className = "flex items-center justify-center p-3 bg-emerald-50 border border-emerald-500 text-emerald-700 rounded-xl cursor-pointer font-bold text-xs shadow-sm transition-all duration-300";
                kaderBtn.className = "flex items-center justify-center p-3 bg-white border border-slate-100 text-slate-500 hover:border-emerald-500 rounded-xl cursor-pointer font-bold text-xs transition-all duration-300";
                userRadio.checked = true;
                kaderRadio.checked = false;
                addressContainer.classList.remove('hidden');
                addressInput.setAttribute('required', 'required');
            }
        }
    </script>
</body>
</html>
