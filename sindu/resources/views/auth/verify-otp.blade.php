<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP - SINDU</title>
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
                <i data-lucide="shield-check" class="text-white w-6 h-6"></i>
            </div>
            <h1 class="text-3xl font-extrabold tracking-tight bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent">SINDU</h1>
            <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Sistem Posyandu Digital Terpadu</p>
        </div>

        <!-- Verification Card -->
        <div class="bg-white/80 backdrop-blur-md rounded-3xl p-6 sm:p-8 shadow-xl border border-slate-100/50 relative overflow-hidden">
            
            {{-- OTP dikirim ke HP. Banner simulasi telah dihapus. --}}

            <h2 class="text-xl font-bold text-slate-800 mb-2">Verifikasi Kode OTP</h2>
            <p class="text-xs text-slate-500 mb-6 leading-relaxed">
                Kami telah mengirimkan kode verifikasi 6-digit ke email yang tercantum: 
                <span class="font-bold text-slate-700">
                    {{ session('pending_user.email') }}
                </span>. Silakan masukkan kode tersebut di bawah ini.
            </p>

            @if($errors->any())
                <div class="mb-5 p-3.5 bg-red-50 border border-red-100 text-red-700 text-xs font-semibold rounded-xl space-y-1">
                    @foreach($errors->all() as $error)
                        <div class="flex items-center gap-2">
                            <i data-lucide="alert-circle" class="w-4.5 h-4.5 shrink-0"></i>
                            <span>{{ $error }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            <form action="/verify-otp" method="POST" class="space-y-6">
                @csrf
                
                <!-- 6 digit code inputs -->
                <div class="flex justify-between gap-2" id="otp-inputs-container">
                    <input type="text" maxlength="1" class="w-12 h-14 text-center text-xl font-bold text-slate-800 bg-slate-50 border border-slate-100 focus:border-emerald-500 focus:bg-white rounded-xl outline-none transition-all duration-300 shadow-sm" required autofocus>
                    <input type="text" maxlength="1" class="w-12 h-14 text-center text-xl font-bold text-slate-800 bg-slate-50 border border-slate-100 focus:border-emerald-500 focus:bg-white rounded-xl outline-none transition-all duration-300 shadow-sm" required>
                    <input type="text" maxlength="1" class="w-12 h-14 text-center text-xl font-bold text-slate-800 bg-slate-50 border border-slate-100 focus:border-emerald-500 focus:bg-white rounded-xl outline-none transition-all duration-300 shadow-sm" required>
                    <input type="text" maxlength="1" class="w-12 h-14 text-center text-xl font-bold text-slate-800 bg-slate-50 border border-slate-100 focus:border-emerald-500 focus:bg-white rounded-xl outline-none transition-all duration-300 shadow-sm" required>
                    <input type="text" maxlength="1" class="w-12 h-14 text-center text-xl font-bold text-slate-800 bg-slate-50 border border-slate-100 focus:border-emerald-500 focus:bg-white rounded-xl outline-none transition-all duration-300 shadow-sm" required>
                    <input type="text" maxlength="1" class="w-12 h-14 text-center text-xl font-bold text-slate-800 bg-slate-50 border border-slate-100 focus:border-emerald-500 focus:bg-white rounded-xl outline-none transition-all duration-300 shadow-sm" required>
                </div>
                
                <!-- Hidden inputs to store the full otp -->
                <input type="hidden" name="otp" id="otp-full-value">

                <button type="submit" class="w-full flex items-center justify-center space-x-2 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-2xl shadow-md shadow-emerald-600/10 transition-all duration-300 mt-2">
                    <i data-lucide="check-circle-2" class="w-4.5 h-4.5"></i>
                    <span>Verifikasi Kode</span>
                </button>
            </form>

            <div class="mt-6 flex flex-col gap-3 border-t border-slate-100/80 pt-5">
                <form action="/resend-otp" method="POST" class="w-full">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 py-3 bg-slate-50 hover:bg-emerald-50 border border-slate-200 hover:border-emerald-300 text-slate-600 hover:text-emerald-700 font-bold text-xs rounded-2xl transition-all duration-300 shadow-sm">
                        <i data-lucide="mail" class="w-4 h-4 text-emerald-600 shrink-0"></i>
                        <span>Kirim Kode Verifikasi Melalui Email Yang Tercantum</span>
                    </button>
                </form>

                <div class="text-center">
                    <a href="/register" class="text-slate-400 hover:text-slate-600 font-bold text-xs inline-flex items-center gap-1.5 transition-colors">
                        <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                        <span>Kembali ke Registrasi</span>
                    </a>
                </div>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();

            const container = document.getElementById('otp-inputs-container');
            const inputs = container.querySelectorAll('input');
            const fullInput = document.getElementById('otp-full-value');

            // Handle typing and backspacing to automatically shift focus
            inputs.forEach((input, index) => {
                input.addEventListener('input', (e) => {
                    const val = e.target.value;
                    if (val.length === 1 && index < inputs.length - 1) {
                        inputs[index + 1].focus();
                    }
                    updateFullValue();
                });

                input.addEventListener('keydown', (e) => {
                    if (e.key === 'Backspace' && !e.target.value && index > 0) {
                        inputs[index - 1].focus();
                    }
                });

                // support pasting the code
                input.addEventListener('paste', (e) => {
                    e.preventDefault();
                    const text = (e.clipboardData || window.clipboardData).getData('text').trim();
                    if (/^\d{6}$/.test(text)) {
                        for (let i = 0; i < inputs.length; i++) {
                            inputs[i].value = text[i];
                        }
                        updateFullValue();
                        inputs[inputs.length - 1].focus();
                    }
                });
            });

            function updateFullValue() {
                let code = '';
                inputs.forEach(input => {
                    code += input.value;
                });
                fullInput.value = code;
            }
        });
    </script>
</body>
</html>
