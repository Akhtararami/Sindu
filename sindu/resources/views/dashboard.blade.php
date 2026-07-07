<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SINDU - Sistem Informasi & Analitis Posyandu</title>
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS (Vite compiled) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        /* Custom scrollbar for premium feel */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        /* Pulse animations for status */
        @keyframes pulse-soft {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: .85; transform: scale(1.03); }
        }
        .animate-pulse-soft {
            animation: pulse-soft 3s infinite ease-in-out;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-50 via-slate-100 to-emerald-50/40 min-h-screen text-slate-800">
    <!-- Header/Navigation -->
    <header class="sticky top-0 z-30 bg-white/80 backdrop-blur-md border-b border-slate-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 sm:h-20">
                <!-- Logo & Brand -->
                <div class="flex items-center space-x-3">
                    <div class="flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 bg-emerald-600 rounded-2xl shadow-lg shadow-emerald-200">
                        <i data-lucide="activity" class="text-white w-6 h-6"></i>
                    </div>
                    <div>
                        <span class="text-xl sm:text-2xl font-extrabold tracking-tight bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent">SINDU</span>
                        <span class="block text-[10px] sm:text-xs font-semibold uppercase tracking-wider text-slate-400">Sistem Posyandu Digital</span>
                    </div>
                </div>

                <!-- Stats Summary Header (Real-time count badge) -->
                <div class="hidden md:flex items-center space-x-6">
                    <div class="flex items-center space-x-2">
                        <div class="p-2 bg-emerald-50 rounded-lg"><i data-lucide="users" class="text-emerald-600 w-5 h-5"></i></div>
                        <div>
                            <span class="block text-xs font-medium text-slate-400">Total Anak Terdaftar</span>
                            <span id="header-total-anak" class="text-base font-bold text-slate-700">Loading...</span>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="p-2 bg-teal-50 rounded-lg"><i data-lucide="check-circle" class="text-teal-600 w-5 h-5"></i></div>
                        <div>
                            <span class="block text-xs font-medium text-slate-400">Status Gizi Baik</span>
                            <span id="header-gizi-baik" class="text-base font-bold text-slate-700">Loading...</span>
                        </div>
                    </div>
                </div>

                <!-- Actions / Quick Access -->
                <div class="flex items-center space-x-3">
                    <!-- User Details & Role Badge -->
                    <div class="hidden lg:block text-right">
                        <span class="block text-xs font-extrabold text-slate-700 leading-none">{{ Auth::user()->name }}</span>
                        <span class="inline-block px-1.5 py-0.5 mt-1 bg-emerald-50 text-emerald-700 font-bold text-[9px] uppercase tracking-wider rounded border border-emerald-100/50">
                            {{ Auth::user()->role === 'kader' ? 'Kader Posyandu' : 'Orang Tua / User' }}
                        </span>
                    </div>

                    <button onclick="scrollToCalculator()" class="flex items-center space-x-2 px-3 py-2 sm:px-4 sm:py-2.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-semibold text-sm rounded-xl transition-all duration-300">
                        <i data-lucide="calculator" class="w-4 h-4"></i>
                        <span class="hidden sm:inline">Kalkulator Gizi</span>
                    </button>

                    <a href="/chat" class="flex items-center space-x-2 px-3 py-2 sm:px-4 sm:py-2.5 bg-sky-50 hover:bg-sky-100 text-sky-700 font-semibold text-sm rounded-xl transition-all duration-300">
                        <i data-lucide="message-circle" class="w-4 h-4"></i>
                        <span class="hidden sm:inline">Live Chat</span>
                    </a>

                    @if(Auth::user()->isKader())
                    <button onclick="openModal('add-child-modal')" class="flex items-center space-x-2 px-3 py-2 sm:px-4 sm:py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm rounded-xl shadow-md hover:shadow-lg transition-all duration-300">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        <span>Anak Baru</span>
                    </button>
                    @endif

                    <!-- Logout Button -->
                    <form action="/logout" method="POST" class="inline">
                        @csrf
                        <button type="submit" title="Keluar Aplikasi" class="p-2 sm:p-2.5 bg-slate-100 hover:bg-red-50 text-slate-500 hover:text-red-600 rounded-xl transition-all duration-300">
                            <i data-lucide="log-out" class="w-4.5 h-4.5"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Grid -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-6 sm:space-y-8">
        
        <!-- Live posyandu telemetry / notification banner -->
        <div class="bg-gradient-to-r from-emerald-600 to-teal-700 text-white rounded-3xl p-5 sm:p-8 shadow-xl shadow-emerald-950/10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 relative overflow-hidden">
            <!-- Background glow effect -->
            <div class="absolute right-0 top-0 w-80 h-80 bg-white/5 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="space-y-2 z-10">
                <span class="px-2.5 py-1 bg-white/20 text-white text-[10px] font-bold uppercase tracking-wider rounded-full">Dashboard Utama</span>
                <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight">Pantau Tumbuh Kembang Anak Lebih Mudah</h1>
                <p class="text-white/80 text-sm sm:text-base max-w-2xl font-light">Analisis grafik pertumbuhan (KMS) & klasifikasi status gizi balita secara real-time berdasarkan standar WHO.</p>
            </div>
            
            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 w-full md:w-auto min-w-[240px] border border-white/10 z-10">
                <div class="flex items-center space-x-2 mb-2">
                    <span class="flex h-2.5 w-2.5 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-rose-500"></span>
                    </span>
                    <span class="text-xs font-semibold text-emerald-100 uppercase tracking-wider">Pemeriksaan Hari Ini</span>
                </div>
                <div id="live-telemetry-feed" class="text-sm space-y-1 text-white font-medium">
                    <!-- Dynamic live updates -->
                    <p class="animate-fade-in flex justify-between gap-4"><span class="text-white/80 font-normal">Loading updates...</span></p>
                </div>
            </div>
        </div>

        <!-- Main Dashboard Split Screen -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">
            
            <!-- Left Column: Child Selection & Quick Info -->
            <div class="lg:col-span-1 space-y-6 sm:space-y-8">
                <!-- Card 1: Directory Anak -->
                <div class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-100 shadow-sm flex flex-col h-[400px]">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-bold text-slate-800 flex items-center space-x-2">
                            <i data-lucide="folder-heart" class="text-emerald-600 w-5 h-5"></i>
                            <span>Daftar Balita</span>
                        </h2>
                        <span id="child-count-badge" class="px-2 py-0.5 bg-slate-100 text-slate-600 text-xs font-bold rounded-full">0</span>
                    </div>

                    <!-- Search Input -->
                    <div class="relative mb-4">
                        <i data-lucide="search" class="absolute left-3.5 top-3 text-slate-400 w-4.5 h-4.5"></i>
                        <input id="search-child-input" type="text" oninput="filterChildren()" placeholder="Cari nama balita..." class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-100 focus:border-emerald-500 focus:bg-white text-sm rounded-xl outline-none transition-all duration-300">
                    </div>

                    <!-- Children List Wrapper -->
                    <div id="children-list-container" class="flex-1 overflow-y-auto space-y-2.5 pr-1">
                        <!-- Dynamic list populated by JS -->
                        <div class="text-center py-8 text-slate-400"><i data-lucide="loader" class="animate-spin w-8 h-8 mx-auto mb-2 text-emerald-500"></i> Memuat data...</div>
                    </div>
                </div>

                <!-- Card 2: Balita Terpilih Detail -->
                <div id="active-child-card" class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-100 shadow-sm relative overflow-hidden transition-all duration-300 min-h-[300px]">
                    <!-- Placeholder when no child selected -->
                    <div id="active-child-placeholder" class="hidden absolute inset-0 bg-white flex flex-col items-center justify-center p-6 text-center">
                        <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mb-4"><i data-lucide="user-x" class="text-slate-400 w-8 h-8"></i></div>
                        <h3 class="text-slate-700 font-semibold mb-1">Pilih Balita</h3>
                        <p class="text-slate-400 text-xs max-w-[200px]">Silakan pilih nama balita dari daftar di atas untuk melihat analitis tumbuh kembang.</p>
                    </div>

                    <!-- Actual content loaded by JS -->
                    <div class="space-y-5">
                        <div class="flex items-start justify-between">
                            <div class="space-y-1">
                                <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 text-[10px] font-bold uppercase tracking-wider rounded">Profil Balita</span>
                                <h3 id="active-child-nama" class="text-xl font-bold text-slate-800 leading-tight">Nama Balita</h3>
                                <p id="active-child-ibu" class="text-xs text-slate-400 font-medium">Ibu: Nama Ibu</p>
                            </div>
                            <div id="active-child-gender-icon" class="w-10 h-10 rounded-xl flex items-center justify-center bg-blue-50 text-blue-500 font-bold text-sm">
                                L
                            </div>
                        </div>

                        <hr class="border-slate-100">

                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-slate-50/50 rounded-2xl p-3 border border-slate-100/50">
                                <span class="block text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Usia Saat Ini</span>
                                <span id="active-child-usia" class="text-base font-extrabold text-slate-700">0 Bulan</span>
                            </div>
                            <div class="bg-slate-50/50 rounded-2xl p-3 border border-slate-100/50">
                                <span class="block text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Tgl Lahir</span>
                                <span id="active-child-lahir" class="text-sm font-bold text-slate-700">01-01-2025</span>
                            </div>
                        </div>

                        <!-- Status Gizi Terbaru Badge -->
                        <div id="active-child-status-container" class="bg-slate-50 rounded-2xl p-4 flex items-center justify-between border border-slate-100">
                            <div>
                                <span class="block text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Status Gizi (BB/U)</span>
                                <span id="active-child-status-label" class="text-lg font-black text-emerald-600 leading-none">Normal</span>
                            </div>
                            <div id="active-child-status-badge" class="px-3.5 py-1.5 rounded-full font-bold text-xs bg-emerald-100 text-emerald-700 animate-pulse-soft">
                                Gizi Baik
                            </div>
                        </div>

                        @if(Auth::user()->isKader())
                        <button onclick="openModal('add-record-modal')" class="w-full flex items-center justify-center space-x-2 py-3 bg-slate-900 hover:bg-slate-800 text-white font-semibold text-sm rounded-2xl transition-all duration-300">
                            <i data-lucide="clipboard-plus" class="w-4 h-4"></i>
                            <span>Catat Hasil Periksa Baru</span>
                        </button>
                        @else
                        <div class="p-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-center text-xs text-slate-400 font-bold">
                            <i data-lucide="lock" class="inline w-3.5 h-3.5 mr-0.5 mb-0.5"></i> Mode Lihat (Hanya Kader yang dapat mencatat)
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column: Visual Charts & Analytics -->
            <div class="lg:col-span-2 space-y-6 sm:space-y-8">
                
                <!-- Tab Switching Panel (Charts vs History Data Table) -->
                <div class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-100 shadow-sm space-y-6">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-slate-100 pb-4">
                        <div>
                            <h2 class="text-lg font-bold text-slate-800 flex items-center space-x-2">
                                <i data-lucide="bar-chart-3" class="text-emerald-600 w-5 h-5"></i>
                                <span>Analisis Grafik Pertumbuhan (KMS)</span>
                            </h2>
                            <p class="text-xs text-slate-400 mt-1">Garis tebal berwarna merepresentasikan data anak Anda dibandingkan dengan kurva WHO.</p>
                        </div>
                        
                        <!-- Toggle Button Chart VS Data -->
                        <div class="flex bg-slate-100 p-1 rounded-xl self-stretch sm:self-auto">
                            <button id="tab-chart-btn" onclick="switchTab('chart')" class="flex-1 sm:flex-initial px-4 py-2 text-xs font-bold rounded-lg transition-all duration-300 bg-white text-emerald-700 shadow-sm">
                                <i data-lucide="line-chart" class="inline w-3.5 h-3.5 mr-1 mb-0.5"></i>Grafik KMS
                            </button>
                            <button id="tab-data-btn" onclick="switchTab('data')" class="flex-1 sm:flex-initial px-4 py-2 text-xs font-bold rounded-lg transition-all duration-300 text-slate-500 hover:text-slate-700">
                                <i data-lucide="database" class="inline w-3.5 h-3.5 mr-1 mb-0.5"></i>Riwayat Data
                            </button>
                        </div>
                    </div>

                    <!-- Panel 1: Charts -->
                    <div id="panel-chart-container" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Metric Weights -->
                            <div class="bg-emerald-50/50 border border-emerald-100/50 p-4 rounded-2xl flex items-center justify-between">
                                <div>
                                    <span class="block text-xs font-semibold text-emerald-800/80">Berat Badan Terbaru</span>
                                    <span id="metric-berat" class="text-3xl font-extrabold text-slate-800">- <span class="text-sm font-bold text-slate-500">kg</span></span>
                                </div>
                                <div class="w-12 h-12 bg-emerald-600 rounded-xl flex items-center justify-center text-white"><i data-lucide="scale" class="w-6 h-6"></i></div>
                            </div>
                            <!-- Metric Heights -->
                            <div class="bg-teal-50/50 border border-teal-100/50 p-4 rounded-2xl flex items-center justify-between">
                                <div>
                                    <span class="block text-xs font-semibold text-teal-800/80">Tinggi Badan Terbaru</span>
                                    <span id="metric-tinggi" class="text-3xl font-extrabold text-slate-800">- <span class="text-sm font-bold text-slate-500">cm</span></span>
                                </div>
                                <div class="w-12 h-12 bg-teal-600 rounded-xl flex items-center justify-center text-white"><i data-lucide="ruler" class="w-6 h-6"></i></div>
                            </div>
                        </div>

                        <!-- Real KMS Chart container -->
                        <div class="bg-slate-50/50 rounded-2xl p-4 border border-slate-100/80 relative">
                            <div id="chart-loader" class="absolute inset-0 bg-white/75 backdrop-blur-xs flex items-center justify-center z-10 hidden">
                                <i data-lucide="loader" class="animate-spin text-emerald-600 w-8 h-8"></i>
                            </div>
                            <div id="kms-chart" class="w-full h-[350px]"></div>
                        </div>
                    </div>

                    <!-- Panel 2: Table Data -->
                    <div id="panel-data-container" class="hidden overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-slate-100 text-slate-400 text-xs font-bold uppercase tracking-wider">
                                    <th class="py-3.5 px-4">Bulan Ke</th>
                                    <th class="py-3.5 px-4">Tgl Periksa</th>
                                    <th class="py-3.5 px-4 text-center">Berat (kg)</th>
                                    <th class="py-3.5 px-4 text-center">Tinggi (cm)</th>
                                    <th class="py-3.5 px-4 text-right">Status Gizi</th>
                                </tr>
                            </thead>
                            <tbody id="history-table-body" class="text-sm divide-y divide-slate-50">
                                <!-- Dynamic rows -->
                            </tbody>
                        </table>
                        <div id="history-table-empty" class="text-center py-12 text-slate-400 hidden">
                            <i data-lucide="inbox" class="w-10 h-10 mx-auto mb-2 text-slate-300"></i>
                            <span>Tidak ada riwayat pemeriksaan untuk balita ini.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section: Interactive Nutritional Calculator (Kalkulator Gizi) -->
        <section id="nutrition-calculator" class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-100 shadow-sm scroll-mt-24">
            <div class="max-w-4xl mx-auto space-y-8">
                <!-- Section Title -->
                <div class="text-center max-w-lg mx-auto space-y-2">
                    <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 text-xs font-bold uppercase tracking-wider rounded-full">Alat Diagnosis Mandiri</span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-800">Kalkulator Klasifikasi Status Gizi</h2>
                    <p class="text-xs sm:text-sm text-slate-400 font-light">Masukkan parameter berat, tinggi, jenis kelamin, dan usia anak untuk menghitung klasifikasi gizi instant.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
                    <!-- Form inputs -->
                    <form id="calculator-form" onsubmit="calculateNutrition(event)" class="bg-slate-50/50 p-5 sm:p-6 rounded-3xl border border-slate-100 space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Jenis Kelamin</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <label class="flex items-center justify-center py-3 bg-white border border-slate-100 hover:border-emerald-500 rounded-xl cursor-pointer transition-all duration-300 font-bold text-sm relative">
                                        <input type="radio" name="calc_gender" value="L" checked class="absolute opacity-0">
                                        <i data-lucide="baby" class="w-4 h-4 mr-1 text-blue-500"></i> Laki-Laki
                                    </label>
                                    <label class="flex items-center justify-center py-3 bg-white border border-slate-100 hover:border-emerald-500 rounded-xl cursor-pointer transition-all duration-300 font-bold text-sm relative">
                                        <input type="radio" name="calc_gender" value="P" class="absolute opacity-0">
                                        <i data-lucide="baby" class="w-4 h-4 mr-1 text-pink-500"></i> Perempuan
                                    </label>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Usia Anak</label>
                                <div class="relative">
                                    <input type="number" name="calc_age" required min="0" max="60" placeholder="0 - 60" class="w-full px-4 py-3 bg-white border border-slate-100 rounded-xl outline-none focus:border-emerald-500 text-sm font-bold transition-all duration-300">
                                    <span class="absolute right-3.5 top-3 text-xs font-bold text-slate-400 uppercase tracking-wider">Bulan</span>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Berat Badan</label>
                                <div class="relative">
                                    <input type="number" step="0.1" name="calc_weight" required min="0.5" max="50" placeholder="0.0" class="w-full px-4 py-3 bg-white border border-slate-100 rounded-xl outline-none focus:border-emerald-500 text-sm font-bold transition-all duration-300">
                                    <span class="absolute right-3.5 top-3 text-xs font-bold text-slate-400 uppercase tracking-wider">Kg</span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tinggi Badan</label>
                                <div class="relative">
                                    <input type="number" step="0.1" name="calc_height" required min="30" max="130" placeholder="0.0" class="w-full px-4 py-3 bg-white border border-slate-100 rounded-xl outline-none focus:border-emerald-500 text-sm font-bold transition-all duration-300">
                                    <span class="absolute right-3.5 top-3 text-xs font-bold text-slate-400 uppercase tracking-wider">Cm</span>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="w-full flex items-center justify-center space-x-2 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-2xl shadow-md shadow-emerald-600/10 transition-all duration-300">
                            <i data-lucide="calculator" class="w-4 h-4"></i>
                            <span>Hitung Sekarang</span>
                        </button>
                    </form>

                    <!-- Results block -->
                    <div id="calculator-result" class="bg-slate-50 p-6 sm:p-8 rounded-3xl border border-dashed border-slate-200 h-full flex flex-col justify-center items-center text-center">
                        <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mb-4"><i data-lucide="heart" class="text-slate-400 w-8 h-8"></i></div>
                        <h3 class="font-bold text-slate-700 text-lg mb-2">Hasil Perhitungan Gizi</h3>
                        <p class="text-xs text-slate-400 max-w-[280px]">Silakan masukkan data balita di sebelah kiri dan klik tombol untuk mendapatkan hasil evaluasi dan rekomendasi.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section: Educational Resources -->
        <section class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">
            <div class="bg-gradient-to-tr from-emerald-500 to-emerald-600 text-white rounded-3xl p-6 shadow-md relative overflow-hidden flex flex-col justify-between h-[220px]">
                <i data-lucide="apple" class="absolute -right-4 -bottom-4 w-28 h-28 opacity-10 rotate-12"></i>
                <div class="space-y-2">
                    <span class="px-2 py-0.5 bg-white/20 text-white text-[10px] font-bold uppercase rounded">Gizi Sehat</span>
                    <h3 class="text-lg font-bold">1000 Hari Pertama</h3>
                    <p class="text-xs text-white/80 leading-relaxed font-light">Masa krusial bagi tumbuh kembang anak untuk mencegah stunting secara dini melalui pemenuhan gizi makro & mikro lengkap.</p>
                </div>
                <a href="https://promkes.kemkes.go.id" target="_blank" class="flex items-center text-xs font-bold text-white hover:underline mt-4">
                    Pelajari Selengkapnya <i data-lucide="arrow-up-right" class="w-3.5 h-3.5 ml-1"></i>
                </a>
            </div>

            <div class="bg-gradient-to-tr from-teal-500 to-teal-600 text-white rounded-3xl p-6 shadow-md relative overflow-hidden flex flex-col justify-between h-[220px]">
                <i data-lucide="shield-check" class="absolute -right-4 -bottom-4 w-28 h-28 opacity-10 rotate-12"></i>
                <div class="space-y-2">
                    <span class="px-2 py-0.5 bg-white/20 text-white text-[10px] font-bold uppercase rounded">Jadwal</span>
                    <h3 class="text-lg font-bold">Jadwal Imunisasi Lengkap</h3>
                    <p class="text-xs text-white/80 leading-relaxed font-light">Pantau jadwal imunisasi anak Anda ke posyandu terdekat untuk pembentukan kekebalan tubuh balita.</p>
                </div>
                <a href="https://idai.or.id" target="_blank" class="flex items-center text-xs font-bold text-white hover:underline mt-4">
                    Jadwal Resmi IDAI <i data-lucide="arrow-up-right" class="w-3.5 h-3.5 ml-1"></i>
                </a>
            </div>

            <div class="bg-gradient-to-tr from-cyan-500 to-cyan-600 text-white rounded-3xl p-6 shadow-md relative overflow-hidden flex flex-col justify-between h-[220px]">
                <i data-lucide="heart-handshake" class="absolute -right-4 -bottom-4 w-28 h-28 opacity-10 rotate-12"></i>
                <div class="space-y-2">
                    <span class="px-2 py-0.5 bg-white/20 text-white text-[10px] font-bold uppercase rounded">Dukungan</span>
                    <h3 class="text-lg font-bold">Konsultasi Medis Posyandu</h3>
                    <p class="text-xs text-white/80 leading-relaxed font-light">Hubungi bidan desa atau kader Posyandu untuk berkonsultasi mengenai keluhan gizi dan pola makan anak Anda.</p>
                </div>
                <a href="#nutrition-calculator" class="flex items-center text-xs font-bold text-white hover:underline mt-4">
                    Konsultasi Gratis <i data-lucide="arrow-down" class="w-3.5 h-3.5 ml-1"></i>
                </a>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-500 border-t border-slate-800 mt-12 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-4">
            <div class="flex items-center justify-center space-x-2">
                <div class="w-8 h-8 bg-emerald-600 rounded-lg flex items-center justify-center text-white"><i data-lucide="activity" class="w-4.5 h-4.5"></i></div>
                <span class="text-white font-extrabold text-base tracking-wider uppercase">SINDU</span>
            </div>
            <p class="text-xs text-slate-400 max-w-md mx-auto">Sistem Posyandu Digital terpadu. Menyediakan informasi analitis KMS, klasifikasi status gizi real-time.</p>
            <p class="text-[10px] text-slate-600">&copy; 2026 SINDU (Sistem Posyandu Digital). All rights reserved.</p>
        </div>
    </footer>

    <!-- MODAL 1: Tambah Anak Baru -->
    <div id="add-child-modal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl relative animate-pulse-soft/30 border border-slate-100">
            <button onclick="closeModal('add-child-modal')" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600"><i data-lucide="x" class="w-6 h-6"></i></button>
            
            <h3 class="text-lg font-bold text-slate-800 flex items-center space-x-2 mb-4">
                <i data-lucide="baby" class="text-emerald-600 w-5 h-5"></i>
                <span>Pendaftaran Balita Baru</span>
            </h3>

            <form id="add-child-form" onsubmit="submitChild(event)" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Balita</label>
                    <input type="text" name="nama" required placeholder="Nama Lengkap Balita" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl outline-none focus:border-emerald-500 focus:bg-white text-sm font-semibold transition-all duration-300">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Jenis Kelamin</label>
                        <select name="jenis_kelamin" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl outline-none focus:border-emerald-500 focus:bg-white text-sm font-semibold transition-all duration-300">
                            <option value="L">Laki-Laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl outline-none focus:border-emerald-500 focus:bg-white text-sm font-semibold transition-all duration-300">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Ibu Kandung</label>
                    <input type="text" name="nama_ibu" required placeholder="Nama Ibu Kandung" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl outline-none focus:border-emerald-500 focus:bg-white text-sm font-semibold transition-all duration-300">
                </div>

                <button type="submit" class="w-full flex items-center justify-center space-x-2 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-2xl shadow-md shadow-emerald-600/10 transition-all duration-300">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    <span>Daftarkan Balita</span>
                </button>
            </form>
        </div>
    </div>

    <!-- MODAL 2: Tambah Catatan Pemeriksaan -->
    <div id="add-record-modal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl relative border border-slate-100">
            <button onclick="closeModal('add-record-modal')" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600"><i data-lucide="x" class="w-6 h-6"></i></button>
            
            <h3 class="text-lg font-bold text-slate-800 flex items-center space-x-2 mb-4">
                <i data-lucide="clipboard-list" class="text-emerald-600 w-5 h-5"></i>
                <span>Catat Pemeriksaan Bulanan</span>
            </h3>

            <form id="add-record-form" onsubmit="submitRecord(event)" class="space-y-4">
                @csrf
                <input type="hidden" name="child_id" id="modal-record-child-id">
                
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Nama Balita</label>
                    <input type="text" id="modal-record-child-nama" readonly class="w-full px-4 py-2.5 bg-slate-100 border border-slate-100 rounded-xl outline-none text-sm font-bold text-slate-600">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Usia (Bulan)</label>
                        <input type="number" name="umur_bulan" required min="0" max="60" placeholder="0 - 60" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl outline-none focus:border-emerald-500 focus:bg-white text-sm font-semibold transition-all duration-300">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tanggal Periksa</label>
                        <input type="date" name="tanggal_periksa" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl outline-none focus:border-emerald-500 focus:bg-white text-sm font-semibold transition-all duration-300">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Berat Badan (Kg)</label>
                        <input type="number" step="0.01" name="berat_badan" required min="0.5" max="50" placeholder="Berat (kg)" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl outline-none focus:border-emerald-500 focus:bg-white text-sm font-semibold transition-all duration-300">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tinggi Badan (Cm)</label>
                        <input type="number" step="0.01" name="tinggi_badan" required min="30" max="130" placeholder="Tinggi (cm)" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl outline-none focus:border-emerald-500 focus:bg-white text-sm font-semibold transition-all duration-300">
                    </div>
                </div>

                <button type="submit" class="w-full flex items-center justify-center space-x-2 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-2xl shadow-md shadow-emerald-600/10 transition-all duration-300">
                    <i data-lucide="check" class="w-4 h-4"></i>
                    <span>Simpan Pemeriksaan</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Scripting (Vanilla JS for ultra responsiveness, Chart.js integrations & live updates) -->
    <script>
        // Global variables
        let childrenData = [];
        let activeChild = null;
        let chart = null;
        let activeTab = 'chart';

        // Initialize App
        document.addEventListener('DOMContentLoaded', () => {
            fetchChildren();
            setupLiveTelemetry();
            
            // Set date inputs to today by default
            const today = new Date().toISOString().split('T')[0];
            document.querySelectorAll('input[type="date"]').forEach(el => el.value = today);

            // Re-render Lucide icons
            lucide.createIcons();
        });

        // Fetch children list from database
        function fetchChildren(selectId = null) {
            fetch('/api/children')
                .then(res => res.json())
                .then(data => {
                    childrenData = data;
                    renderChildrenList(selectId);
                    updateGlobalStats();
                })
                .catch(err => {
                    console.error('Error fetching children:', err);
                });
        }

        // Switch panel tabs
        function switchTab(tab) {
            activeTab = tab;
            const chartBtn = document.getElementById('tab-chart-btn');
            const dataBtn = document.getElementById('tab-data-btn');
            const chartPanel = document.getElementById('panel-chart-container');
            const dataPanel = document.getElementById('panel-data-container');

            if (tab === 'chart') {
                chartBtn.classList.add('bg-white', 'text-emerald-700', 'shadow-sm');
                chartBtn.classList.remove('text-slate-500');
                dataBtn.classList.remove('bg-white', 'text-emerald-700', 'shadow-sm');
                dataBtn.classList.add('text-slate-500');
                chartPanel.classList.remove('hidden');
                dataPanel.classList.add('hidden');
                if (activeChild) {
                    setTimeout(() => renderKMSChart(), 50); // slight delay to ensure container is fully sized
                }
            } else {
                dataBtn.classList.add('bg-white', 'text-emerald-700', 'shadow-sm');
                dataBtn.classList.remove('text-slate-500');
                chartBtn.classList.remove('bg-white', 'text-emerald-700', 'shadow-sm');
                chartBtn.classList.add('text-slate-500');
                dataPanel.classList.remove('hidden');
                chartPanel.classList.add('hidden');
                if (activeChild) {
                    renderHistoryTable();
                }
            }
        }

        // Render Sidebar Children List
        function renderChildrenList(selectId = null) {
            const container = document.getElementById('children-list-container');
            const badge = document.getElementById('child-count-badge');
            
            if (childrenData.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-10 text-slate-400">
                        <i data-lucide="users-2" class="w-8 h-8 mx-auto mb-2 text-slate-300"></i>
                        <p class="text-xs">Belum ada balita terdaftar.</p>
                    </div>
                `;
                badge.innerText = '0';
                lucide.createIcons();
                setActiveChild(null);
                return;
            }

            badge.innerText = childrenData.length;
            container.innerHTML = '';
            
            childrenData.forEach(child => {
                const totalRecords = child.records.length;
                const lastRecord = totalRecords > 0 ? child.records[totalRecords - 1] : null;
                const status = lastRecord ? lastRecord.status_gizi : 'Belum Ada';
                
                let statusClass = 'bg-slate-100 text-slate-600';
                if (status === 'Gizi Baik') statusClass = 'bg-emerald-50 text-emerald-700 border border-emerald-100/50';
                else if (status === 'Gizi Kurang') statusClass = 'bg-orange-50 text-orange-700 border border-orange-100/50';
                else if (status === 'Gizi Buruk') statusClass = 'bg-red-50 text-red-700 border border-red-100/50';
                else if (status === 'Gizi Lebih') statusClass = 'bg-amber-50 text-amber-700 border border-amber-100/50';

                const ageText = lastRecord ? `${lastRecord.umur_bulan} Bulan` : 'Usia N/A';
                const isSelected = activeChild && activeChild.id === child.id;

                const item = document.createElement('div');
                item.className = `child-item flex items-center justify-between p-3.5 rounded-2xl cursor-pointer border hover:border-emerald-500 hover:bg-slate-50/50 transition-all duration-300 ${isSelected ? 'border-emerald-500 bg-emerald-50/30' : 'border-slate-100/50'}`;
                item.onclick = () => selectChild(child.id);
                item.innerHTML = `
                    <div class="flex items-center space-x-3 min-w-0">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center font-bold text-xs shrink-0 ${child.jenis_kelamin === 'L' ? 'bg-blue-50 text-blue-600' : 'bg-pink-50 text-pink-600'}">
                            ${child.jenis_kelamin}
                        </div>
                        <div class="min-w-0">
                            <span class="block font-bold text-slate-700 text-sm truncate leading-tight">${child.nama}</span>
                            <span class="text-[10px] text-slate-400 font-medium">${ageText} • Ibu: ${child.nama_ibu || '-'}</span>
                        </div>
                    </div>
                    <span class="px-2.5 py-1 text-[10px] font-bold rounded-lg shrink-0 ${statusClass}">${status}</span>
                `;
                container.appendChild(item);
            });

            // Automatically select first child if none selected and selectId is not specified
            if (childrenData.length > 0) {
                if (selectId) {
                    selectChild(selectId);
                } else if (!activeChild) {
                    selectChild(childrenData[0].id);
                } else {
                    // Update active child references in case of updates
                    selectChild(activeChild.id);
                }
            }
        }

        // Filter children inside search
        function filterChildren() {
            const query = document.getElementById('search-child-input').value.toLowerCase();
            const items = document.querySelectorAll('.child-item');
            
            items.forEach((item, idx) => {
                const child = childrenData[idx];
                if (child.nama.toLowerCase().includes(query) || (child.nama_ibu && child.nama_ibu.toLowerCase().includes(query))) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        // Select a child and load their data
        function selectChild(id) {
            const child = childrenData.find(c => c.id === id);
            if (!child) return;
            
            activeChild = child;
            
            // Highlight selected child item visually
            const items = document.querySelectorAll('.child-item');
            childrenData.forEach((c, idx) => {
                if (items[idx]) {
                    if (c.id === id) {
                        items[idx].classList.add('border-emerald-500', 'bg-emerald-50/30');
                    } else {
                        items[idx].classList.remove('border-emerald-500', 'bg-emerald-50/30');
                    }
                }
            });

            setActiveChild(child);
        }

        // Render Active Child Data
        function setActiveChild(child) {
            const cardPlaceholder = document.getElementById('active-child-placeholder');
            
            if (!child) {
                cardPlaceholder.classList.remove('hidden');
                return;
            }

            cardPlaceholder.classList.add('hidden');

            // Render Profile Card Details
            document.getElementById('active-child-nama').innerText = child.nama;
            document.getElementById('active-child-ibu').innerText = `Ibu: ${child.nama_ibu || '-'}`;
            
            const genderIcon = document.getElementById('active-child-gender-icon');
            genderIcon.innerText = child.jenis_kelamin;
            if (child.jenis_kelamin === 'L') {
                genderIcon.className = "w-10 h-10 rounded-xl flex items-center justify-center bg-blue-50 text-blue-500 font-bold text-sm shrink-0";
            } else {
                genderIcon.className = "w-10 h-10 rounded-xl flex items-center justify-center bg-pink-50 text-pink-500 font-bold text-sm shrink-0";
            }

            const totalRecords = child.records.length;
            const lastRecord = totalRecords > 0 ? child.records[totalRecords - 1] : null;

            // Date of birth parsing
            const dob = new Date(child.tanggal_lahir);
            document.getElementById('active-child-lahir').innerText = dob.toLocaleDateString('id-ID', {day: '2-digit', month: '2-digit', year: 'numeric'});
            
            // Age Calculation in Months
            const ageMonths = lastRecord ? lastRecord.umur_bulan : calculateAgeInMonths(dob);
            document.getElementById('active-child-usia').innerText = `${ageMonths} Bulan`;

            // Status Badge
            const statusLabel = document.getElementById('active-child-status-label');
            const statusBadge = document.getElementById('active-child-status-badge');
            const status = lastRecord ? lastRecord.status_gizi : 'Belum Ada';

            statusLabel.innerText = status;
            
            statusBadge.innerText = status;
            statusBadge.className = "px-3.5 py-1.5 rounded-full font-extrabold text-xs animate-pulse-soft shrink-0";
            
            if (status === 'Gizi Baik') {
                statusLabel.className = "text-lg font-black text-emerald-600 leading-none";
                statusBadge.classList.add('bg-emerald-100', 'text-emerald-700');
            } else if (status === 'Gizi Kurang') {
                statusLabel.className = "text-lg font-black text-orange-500 leading-none";
                statusBadge.classList.add('bg-orange-100', 'text-orange-700');
            } else if (status === 'Gizi Buruk') {
                statusLabel.className = "text-lg font-black text-rose-600 leading-none";
                statusBadge.classList.add('bg-rose-100', 'text-rose-700');
            } else if (status === 'Gizi Lebih') {
                statusLabel.className = "text-lg font-black text-amber-500 leading-none";
                statusBadge.classList.add('bg-amber-100', 'text-amber-700');
            } else {
                statusLabel.className = "text-lg font-black text-slate-500 leading-none";
                statusBadge.classList.add('bg-slate-100', 'text-slate-700');
                statusBadge.innerText = 'Pemeriksaan';
            }

            // Metrics Update
            document.getElementById('metric-berat').innerHTML = lastRecord ? `${lastRecord.berat_badan} <span class="text-sm font-bold text-slate-400">kg</span>` : `- <span class="text-sm font-bold text-slate-400">kg</span>`;
            document.getElementById('metric-tinggi').innerHTML = lastRecord ? `${lastRecord.tinggi_badan} <span class="text-sm font-bold text-slate-400">cm</span>` : `- <span class="text-sm font-bold text-slate-400">cm</span>`;

            // Prepare record popup fields
            document.getElementById('modal-record-child-id').value = child.id;
            document.getElementById('modal-record-child-nama').value = child.nama;

            // Load according active tab
            if (activeTab === 'chart') {
                renderKMSChart();
            } else {
                renderHistoryTable();
            }
        }

        // Render KMS Growth Chart
        function renderKMSChart() {
            if (!activeChild) return;

            const chartLoader = document.getElementById('chart-loader');
            chartLoader.classList.remove('hidden');

            const records = activeChild.records;
            const sex = activeChild.jenis_kelamin;

            // Generate WHO Standard Curves for Weight-for-Age (Male / Female 0-24 Months)
            // (or standard curve arrays up to max child age or 24 months)
            const maxAge = Math.max(24, records.length > 0 ? Math.max(...records.map(r => r.umur_bulan)) : 0);
            
            const ages = [];
            for (let i = 0; i <= maxAge; i++) ages.push(i);

            // Medians & Standard Deviations reference curves based on WHO standards
            const standardCurves = {
                'L': { // Male curves
                    median: [3.3, 4.5, 5.6, 6.4, 7.0, 7.5, 7.9, 8.3, 8.6, 8.9, 9.2, 9.4, 9.6, 9.9, 10.1, 10.3, 10.5, 10.7, 10.9, 11.1, 11.3, 11.5, 11.8, 12.0, 12.2],
                    sd2: [3.9, 5.1, 6.3, 7.2, 7.8, 8.4, 8.8, 9.2, 9.6, 9.9, 10.2, 10.5, 10.8, 11.0, 11.3, 11.5, 11.7, 12.0, 12.2, 12.5, 12.7, 12.9, 13.2, 13.4, 13.6],
                    sdMinus2: [2.9, 3.9, 4.9, 5.7, 6.2, 6.7, 7.1, 7.4, 7.7, 8.0, 8.2, 8.4, 8.6, 8.8, 9.0, 9.2, 9.4, 9.5, 9.7, 9.9, 10.1, 10.2, 10.4, 10.5, 10.7],
                    sdMinus3: [2.5, 3.4, 4.3, 5.0, 5.5, 6.0, 6.4, 6.7, 7.0, 7.2, 7.4, 7.6, 7.8, 8.0, 8.2, 8.3, 8.5, 8.7, 8.8, 9.0, 9.2, 9.3, 9.5, 9.6, 9.7]
                },
                'P': { // Female curves
                    median: [3.2, 4.2, 5.1, 5.8, 6.4, 6.9, 7.3, 7.6, 7.9, 8.2, 8.5, 8.7, 8.9, 9.2, 9.4, 9.6, 9.8, 10.0, 10.2, 10.4, 10.6, 10.9, 11.1, 11.3, 11.5],
                    sd2: [3.7, 4.8, 5.8, 6.6, 7.2, 7.8, 8.2, 8.6, 9.0, 9.3, 9.6, 9.9, 10.2, 10.4, 10.7, 10.9, 11.1, 11.4, 11.6, 11.8, 12.0, 12.3, 12.5, 12.7, 13.0],
                    sdMinus2: [2.8, 3.7, 4.5, 5.0, 5.5, 6.0, 6.4, 6.7, 7.0, 7.2, 7.5, 7.7, 7.9, 8.1, 8.3, 8.5, 8.7, 8.9, 9.0, 9.2, 9.4, 9.6, 9.8, 9.9, 10.1],
                    sdMinus3: [2.4, 3.2, 3.9, 4.5, 5.0, 5.4, 5.7, 6.0, 6.3, 6.5, 6.7, 6.9, 7.1, 7.3, 7.5, 7.7, 7.8, 8.0, 8.2, 8.3, 8.5, 8.7, 8.8, 9.0, 9.1]
                }
            };

            const curves = standardCurves[sex] || standardCurves['L'];

            // Pad WHO curves with interpolation if maxAge is > 24
            const finalMedian = [];
            const finalSd2 = [];
            const finalSdMinus2 = [];
            const finalSdMinus3 = [];

            for (let i = 0; i <= maxAge; i++) {
                if (i <= 24) {
                    finalMedian.push(curves.median[i]);
                    finalSd2.push(curves.sd2[i]);
                    finalSdMinus2.push(curves.sdMinus2[i]);
                    finalSdMinus3.push(curves.sdMinus3[i]);
                } else {
                    // Approximate linear growth projection beyond 24 months for visualization
                    const slopeMedian = (curves.median[24] - curves.median[0]) / 24;
                    const slopeSd2 = (curves.sd2[24] - curves.sd2[0]) / 24;
                    const slopeMinus2 = (curves.sdMinus2[24] - curves.sdMinus2[0]) / 24;
                    const slopeMinus3 = (curves.sdMinus3[24] - curves.sdMinus3[0]) / 24;
                    
                    finalMedian.push((curves.median[24] + slopeMedian * (i - 24)).toFixed(1));
                    finalSd2.push((curves.sd2[24] + slopeSd2 * (i - 24)).toFixed(1));
                    finalSdMinus2.push((curves.sdMinus2[24] + slopeMinus2 * (i - 24)).toFixed(1));
                    finalSdMinus3.push((curves.sdMinus3[24] + slopeMinus3 * (i - 24)).toFixed(1));
                }
            }

            // Map actual child growth records
            const childDataPoints = Array(maxAge + 1).fill(null);
            records.forEach(r => {
                if (r.umur_bulan <= maxAge) {
                    childDataPoints[r.umur_bulan] = r.berat_badan;
                }
            });

            // Series Data configuration
            const series = [
                {
                    name: 'Berat Anak Anda',
                    type: 'line',
                    data: childDataPoints
                },
                {
                    name: 'Garis +2 SD (Batas Atas)',
                    type: 'line',
                    data: finalSd2
                },
                {
                    name: 'Median (Gizi Baik)',
                    type: 'line',
                    data: finalMedian
                },
                {
                    name: 'Garis -2 SD (Kurang)',
                    type: 'line',
                    data: finalSdMinus2
                },
                {
                    name: 'Garis -3 SD (Buruk)',
                    type: 'line',
                    data: finalSdMinus3
                }
            ];

            // Destroy previous instance
            if (chart) {
                chart.destroy();
            }

            // Options
            const options = {
                series: series,
                chart: {
                    height: 330,
                    type: 'line',
                    toolbar: { show: false },
                    zoom: { enabled: false }
                },
                colors: ['#059669', '#f59e0b', '#10b981', '#f97316', '#ef4444'], // KMS Color representation
                stroke: {
                    width: [5, 2, 2.5, 2, 2],
                    curve: 'smooth',
                    dashArray: [0, 6, 0, 6, 6]
                },
                markers: {
                    size: [6, 0, 0, 0, 0],
                    colors: ['#ffffff'],
                    strokeColors: ['#059669'],
                    strokeWidth: 3,
                    hover: { size: 8 }
                },
                grid: {
                    borderColor: '#f1f5f9',
                    strokeDashArray: 4
                },
                xaxis: {
                    categories: ages,
                    title: {
                        text: 'Usia (Bulan)',
                        style: { fontFamily: 'Plus Jakarta Sans', fontWeight: 600, color: '#94a3b8' }
                    },
                    labels: {
                        style: { colors: '#94a3b8', fontSize: '11px' }
                    }
                },
                yaxis: {
                    title: {
                        text: 'Berat Badan (Kg)',
                        style: { fontFamily: 'Plus Jakarta Sans', fontWeight: 600, color: '#94a3b8' }
                    },
                    labels: {
                        style: { colors: '#94a3b8', fontSize: '11px' }
                    }
                },
                tooltip: {
                    shared: true,
                    intersect: false,
                    y: {
                        formatter: function (y) {
                            return y !== null && y !== undefined ? y + ' kg' : null;
                        }
                    }
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'center',
                    fontFamily: 'Plus Jakarta Sans',
                    fontSize: '11px',
                    markers: { radius: 12 }
                }
            };

            chart = new ApexCharts(document.querySelector("#kms-chart"), options);
            chart.render();

            setTimeout(() => {
                chartLoader.classList.add('hidden');
            }, 100);
        }

        // Render History Table
        function renderHistoryTable() {
            const tbody = document.getElementById('history-table-body');
            const empty = document.getElementById('history-table-empty');
            
            tbody.innerHTML = '';
            
            if (!activeChild || activeChild.records.length === 0) {
                empty.classList.remove('hidden');
                return;
            }

            empty.classList.add('hidden');
            
            // Order records reversed for history (newest first)
            const revRecords = [...activeChild.records].reverse();

            revRecords.forEach(r => {
                const date = new Date(r.tanggal_periksa).toLocaleDateString('id-ID', {day: '2-digit', month: '2-digit', year: 'numeric'});
                
                let statusBadge = '';
                if (r.status_gizi === 'Gizi Baik') statusBadge = '<span class="px-2 py-1 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-lg border border-emerald-100/50">Gizi Baik</span>';
                else if (r.status_gizi === 'Gizi Kurang') statusBadge = '<span class="px-2 py-1 bg-orange-50 text-orange-700 text-xs font-bold rounded-lg border border-orange-100/50">Gizi Kurang</span>';
                else if (r.status_gizi === 'Gizi Buruk') statusBadge = '<span class="px-2 py-1 bg-red-50 text-red-700 text-xs font-bold rounded-lg border border-red-100/50">Gizi Buruk</span>';
                else if (r.status_gizi === 'Gizi Lebih') statusBadge = '<span class="px-2 py-1 bg-amber-50 text-amber-700 text-xs font-bold rounded-lg border border-amber-100/50">Gizi Lebih</span>';

                const row = document.createElement('tr');
                row.className = "hover:bg-slate-50/50 transition-colors duration-150 border-b border-slate-50";
                row.innerHTML = `
                    <td class="py-3.5 px-4 font-bold text-slate-700">Bulan Ke-${r.umur_bulan}</td>
                    <td class="py-3.5 px-4 text-slate-500 font-medium">${date}</td>
                    <td class="py-3.5 px-4 text-center font-bold text-slate-700">${r.berat_badan} kg</td>
                    <td class="py-3.5 px-4 text-center font-bold text-slate-700">${r.tinggi_badan} cm</td>
                    <td class="py-3.5 px-4 text-right">${statusBadge}</td>
                `;
                tbody.appendChild(row);

                if (r.keluhan || r.solusi) {
                    const complaintRow = document.createElement('tr');
                    complaintRow.className = "bg-slate-50/30 border-b border-slate-100/50";
                    complaintRow.innerHTML = `
                        <td colspan="5" class="px-6 py-2">
                            <div class="flex flex-col sm:flex-row gap-3">
                                ${r.keluhan ? `
                                <div class="flex-1 bg-amber-50/80 border border-amber-100/50 p-2.5 rounded-xl">
                                    <span class="font-extrabold text-[10px] text-amber-800 uppercase tracking-wider flex items-center gap-1 mb-1">
                                        <i data-lucide="message-square-warning" class="w-3.5 h-3.5"></i> Keluhan Dicatat
                                    </span> 
                                    <p class="text-xs text-slate-700 font-semibold leading-relaxed">${r.keluhan}</p>
                                </div>` : ''}
                                
                                ${r.solusi ? `
                                <div class="flex-1 bg-emerald-50/80 border border-emerald-100/50 p-2.5 rounded-xl">
                                    <span class="font-extrabold text-[10px] text-emerald-800 uppercase tracking-wider flex items-center gap-1 mb-1">
                                        <i data-lucide="sparkles" class="w-3.5 h-3.5"></i> Solusi / Saran Medis
                                    </span> 
                                    <p class="text-xs text-slate-700 font-semibold leading-relaxed">${r.solusi}</p>
                                </div>` : ''}
                            </div>
                        </td>
                    `;
                    tbody.appendChild(complaintRow);
                }
            });

            lucide.createIcons();
        }

        // Helper: Calculate age in months
        function calculateAgeInMonths(birthDate) {
            const today = new Date();
            let months = (today.getFullYear() - birthDate.getFullYear()) * 12;
            months -= birthDate.getMonth();
            months += today.getMonth();
            return months <= 0 ? 0 : months;
        }

        // Update Header Summary cards
        function updateGlobalStats() {
            const totalAnak = childrenData.length;
            let totalBaik = 0;

            childrenData.forEach(c => {
                if (c.records.length > 0) {
                    const last = c.records[c.records.length - 1];
                    if (last.status_gizi === 'Gizi Baik') {
                        totalBaik++;
                    }
                }
            });

            document.getElementById('header-total-anak').innerText = `${totalAnak} Balita`;
            document.getElementById('header-gizi-baik').innerText = `${totalBaik} Balita`;
        }

        // Setup Real-time Posyandu updates ticker (Simulated live check-in queue)
        function setupLiveTelemetry() {
            const telemetryFeed = document.getElementById('live-telemetry-feed');
            const kidsNames = ['Naufal', 'Kiara', 'Ahmad', 'Adila', 'Rania', 'Zian', 'Fatimah', 'Gibran', 'Zayn', 'Yasmin'];
            const motherNames = ['Ibu Indah', 'Ibu Astuti', 'Ibu Lisa', 'Ibu Nur', 'Ibu Yeni', 'Ibu Sarah', 'Ibu Maria'];
            
            function generateMockActivity() {
                const name = kidsNames[Math.floor(Math.random() * kidsNames.length)];
                const mother = motherNames[Math.floor(Math.random() * motherNames.length)];
                const weight = (6 + Math.random() * 8).toFixed(1);
                const height = (60 + Math.random() * 25).toFixed(1);
                const age = Math.floor(Math.random() * 24);
                
                // Random status
                const statuses = ['Gizi Baik', 'Gizi Baik', 'Gizi Baik', 'Gizi Baik', 'Gizi Kurang', 'Gizi Lebih'];
                const status = statuses[Math.floor(Math.random() * statuses.length)];
                
                let textClass = 'text-emerald-300';
                if (status === 'Gizi Kurang') textClass = 'text-orange-300';
                if (status === 'Gizi Lebih') textClass = 'text-amber-300';

                telemetryFeed.innerHTML = `
                    <p class="animate-fade-in flex justify-between gap-4">
                        <span class="text-white truncate">✔ <strong>${name}</strong> (${age} Bln) sedang ditimbang</span>
                        <span class="${textClass} shrink-0 text-xs font-bold">${status}</span>
                    </p>
                `;
            }

            generateMockActivity();
            // Rotate every 15 seconds to create a realistic telemetry feel
            setInterval(generateMockActivity, 15000);
        }

        // Handle Modal actions
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }

        // Scroll to calculator
        function scrollToCalculator() {
            document.getElementById('nutrition-calculator').scrollIntoView({ behavior: 'smooth' });
        }

        // Form Submit: Add Child
        function submitChild(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);

            fetch('/api/children', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    closeModal('add-child-modal');
                    form.reset();
                    // Re-set date input to today
                    form.querySelector('input[type="date"]').value = new Date().toISOString().split('T')[0];
                    
                    // Fetch children and auto select the newly added child
                    fetchChildren(data.child.id);
                }
            })
            .catch(err => console.error('Error submitting child:', err));
        }

        // Form Submit: Add Growth Record
        function submitRecord(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);

            fetch('/api/records', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    closeModal('add-record-modal');
                    form.reset();
                    form.querySelector('input[type="date"]').value = new Date().toISOString().split('T')[0];
                    
                    // Re-fetch all data to refresh
                    fetchChildren(activeChild.id);
                }
            })
            .catch(err => console.error('Error submitting record:', err));
        }

        // Local Calculator Action
        function calculateNutrition(event) {
            event.preventDefault();
            const form = event.target;
            
            const sex = form.calc_gender.value;
            const age = parseInt(form.calc_age.value);
            const weight = parseFloat(form.calc_weight.value);
            const height = parseFloat(form.calc_height.value);

            // Medians WHO weight table
            const medians = {
                'L': [3.3, 4.5, 5.6, 6.4, 7.0, 7.5, 7.9, 8.3, 8.6, 8.9, 9.2, 9.4, 9.6, 9.9, 10.1, 10.3, 10.5, 10.7, 10.9, 11.1, 11.3, 11.5, 11.8, 12.0, 12.2],
                'P': [3.2, 4.2, 5.1, 5.8, 6.4, 6.9, 7.3, 7.6, 7.9, 8.2, 8.5, 8.7, 8.9, 9.2, 9.4, 9.6, 9.8, 10.0, 10.2, 10.4, 10.6, 10.9, 11.1, 11.3, 11.5]
            };

            const genderMedians = medians[sex];
            let medianWeight = 10.0; // fallback default

            if (age <= 24) {
                medianWeight = genderMedians[age];
            } else {
                // Interpolated estimation above 24 months
                const base = genderMedians[24];
                const increment = sex === 'L' ? 0.25 : 0.22;
                medianWeight = base + (age - 24) * increment;
            }

            const percentage = (weight / medianWeight) * 100;
            
            let status = 'Gizi Baik';
            let statusBadgeClass = 'bg-emerald-100 text-emerald-700';
            let statusIcon = 'check-circle';
            let description = '';

            if (percentage < 70) {
                status = 'Gizi Buruk';
                statusBadgeClass = 'bg-red-100 text-red-700';
                statusIcon = 'alert-triangle';
                description = 'Status Gizi Buruk membutuhkan penanganan segera dari tenaga medis / Dokter Spesialis Anak. Silakan kunjungi puskesmas terdekat.';
            } else if (percentage >= 70 && percentage < 80) {
                status = 'Gizi Kurang';
                statusBadgeClass = 'bg-orange-100 text-orange-700';
                statusIcon = 'alert-circle';
                description = 'Status Gizi Kurang menunjukkan indikasi kurangnya asupan kalori & protein. Disarankan memberikan makanan berprotein tinggi (telur, susu, daging) serta suplemen vitamin tambahan.';
            } else if (percentage >= 80 && percentage <= 120) {
                status = 'Gizi Baik';
                statusBadgeClass = 'bg-emerald-100 text-emerald-700';
                statusIcon = 'check-circle';
                description = 'Status Gizi Baik (Normal). Teruskan pemberian ASI, makanan dengan gizi seimbang (karbohidrat, sayur, buah, protein), serta rutin lakukan imunisasi di Posyandu.';
            } else {
                status = 'Gizi Lebih';
                statusBadgeClass = 'bg-amber-100 text-amber-700';
                statusIcon = 'shield-alert';
                description = 'Status Gizi Lebih menandakan indikasi obesitas ringan. Batasi konsumsi camilan dengan kadar gula/karbohidrat tinggi dan perbanyak aktivitas aktif bermain fisik anak.';
            }

            // Render result UI
            const resultBox = document.getElementById('calculator-result');
            resultBox.innerHTML = `
                <div class="space-y-4 animate-fade-in w-full">
                    <span class="px-3 py-1 text-xs font-bold rounded-full uppercase tracking-wider ${statusBadgeClass} inline-flex items-center gap-1.5 animate-pulse-soft">
                        <i data-lucide="${statusIcon}" class="w-4 h-4"></i> ${status}
                    </span>
                    <h3 class="font-extrabold text-slate-800 text-2xl">${status}</h3>
                    
                    <div class="grid grid-cols-2 gap-4 max-w-sm mx-auto text-left">
                        <div class="bg-white p-3.5 rounded-2xl border border-slate-100">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase">Index BB/U</span>
                            <span class="text-sm font-black text-slate-700">${percentage.toFixed(1)}% WHO Median</span>
                        </div>
                        <div class="bg-white p-3.5 rounded-2xl border border-slate-100">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase">Berat Standar</span>
                            <span class="text-sm font-black text-slate-700">${medianWeight.toFixed(1)} kg</span>
                        </div>
                    </div>

                    <p class="text-slate-500 text-xs sm:text-sm leading-relaxed max-w-md mx-auto font-light">${description}</p>
                    
                    <button onclick="resetCalculator()" class="mt-4 px-4 py-2 border border-slate-200 hover:border-slate-300 text-slate-500 hover:text-slate-700 font-bold text-xs rounded-xl transition-all duration-300">
                        Hitung Balita Lain
                    </button>
                </div>
            `;
            
            // Re-render lucide icons inside calculator result
            lucide.createIcons();
        }

        // Reset Calculator form
        function resetCalculator() {
            const resultBox = document.getElementById('calculator-result');
            document.getElementById('calculator-form').reset();
            resultBox.innerHTML = `
                <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mb-4"><i data-lucide="heart" class="text-slate-400 w-8 h-8"></i></div>
                <h3 class="font-bold text-slate-700 text-lg mb-2">Hasil Perhitungan Gizi</h3>
                <p class="text-xs text-slate-400 max-w-[280px]">Silakan masukkan data balita di sebelah kiri dan klik tombol untuk mendapatkan hasil evaluasi dan rekomendasi.</p>
            `;
            lucide.createIcons();
        }
    </script>
</body>
</html>
