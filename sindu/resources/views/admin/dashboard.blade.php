<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SINDU Admin - Panel Kader Posyandu</title>
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
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        @keyframes pulse-soft { 0%, 100% { opacity: 1; transform: scale(1); } 50% { opacity: .85; transform: scale(1.02); } }
        .animate-pulse-soft { animation: pulse-soft 3s infinite ease-in-out; }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-50 via-slate-100 to-emerald-50/40 min-h-screen text-slate-800">

    <!-- Header/Navigation -->
    <header class="sticky top-0 z-30 bg-slate-900 text-white border-b border-slate-800 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 sm:h-20">
                <!-- Logo & Brand -->
                <div class="flex items-center space-x-3">
                    <div class="flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 bg-emerald-600 rounded-2xl shadow-lg shadow-emerald-950/20">
                        <i data-lucide="shield-check" class="text-white w-6 h-6"></i>
                    </div>
                    <div>
                        <span class="text-xl sm:text-2xl font-black tracking-tight text-white flex items-center gap-1.5">SINDU <span class="text-xs bg-emerald-500/20 text-emerald-400 font-extrabold uppercase px-1.5 py-0.5 rounded border border-emerald-500/30">Admin</span></span>
                        <span class="block text-[10px] sm:text-xs font-semibold uppercase tracking-wider text-slate-400">Back-End Kelola Data</span>
                    </div>
                </div>

                <!-- Stats Summary Header -->
                <div class="hidden md:flex items-center space-x-6">
                    <div class="flex items-center space-x-2">
                        <div class="p-2 bg-slate-800 rounded-lg text-emerald-400"><i data-lucide="users" class="w-5 h-5"></i></div>
                        <div>
                            <span class="block text-xs font-medium text-slate-400">Total Balita</span>
                            <span id="header-total-anak" class="text-base font-bold text-slate-200">Loading...</span>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="p-2 bg-slate-800 rounded-lg text-teal-400"><i data-lucide="check-circle" class="w-5 h-5"></i></div>
                        <div>
                            <span class="block text-xs font-medium text-slate-400">Timbangan Sehat</span>
                            <span id="header-gizi-baik" class="text-base font-bold text-slate-200">Loading...</span>
                        </div>
                    </div>
                </div>

                <!-- Actions / Quick Access -->
                <div class="flex items-center space-x-3">
                    <div class="hidden lg:block text-right">
                        <span class="block text-xs font-extrabold text-slate-200 leading-none">{{ Auth::user()->name }}</span>
                        <span class="inline-block px-1.5 py-0.5 mt-1 bg-emerald-500/20 text-emerald-400 font-bold text-[9px] uppercase tracking-wider rounded border border-emerald-500/30">
                            Kader Posyandu
                        </span>
                    </div>
                    
                    <button onclick="openModal('add-child-modal')" class="flex items-center space-x-2 px-3 py-2 sm:px-4 sm:py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm rounded-xl shadow-md transition-all duration-300">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        <span>Balita Baru</span>
                    </button>

                    <a href="/chat" class="flex items-center space-x-2 px-3 py-2 sm:px-4 sm:py-2.5 bg-sky-600 hover:bg-sky-700 text-white font-semibold text-sm rounded-xl shadow-md transition-all duration-300">
                        <i data-lucide="message-circle" class="w-4 h-4"></i>
                        <span>Live Chat</span>
                    </a>

                    <!-- Logout Button -->
                    <form action="/logout" method="POST" class="inline">
                        @csrf
                        <button type="submit" title="Keluar Aplikasi" class="p-2 sm:p-2.5 bg-slate-800 hover:bg-red-950 text-slate-400 hover:text-red-400 rounded-xl transition-all duration-300 border border-slate-700/50">
                            <i data-lucide="log-out" class="w-4.5 h-4.5"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Grid -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-6 sm:space-y-8">
        
        <!-- Welcome Jumbotron -->
        <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-slate-950 text-white rounded-3xl p-5 sm:p-8 shadow-xl border border-slate-800 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 relative overflow-hidden">
            <div class="absolute right-0 top-0 w-80 h-80 bg-emerald-500/5 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="space-y-2 z-10">
                <span class="px-2.5 py-1 bg-emerald-500/20 text-emerald-400 text-[10px] font-bold uppercase tracking-wider rounded-full border border-emerald-500/20">Panel Administratif</span>
                <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight">Pusat CRUD Pemeriksaan Anak</h1>
                <p class="text-slate-400 text-sm sm:text-base max-w-2xl font-light">Kelola database balita desa, daftarkan anak, edit profil orang tua, input hasil timbang bulanan, serta hapus data pemeriksaan secara terpusat.</p>
            </div>
            
            <div class="bg-slate-800/40 backdrop-blur-md rounded-2xl p-4 w-full md:w-auto min-w-[240px] border border-slate-700/50 z-10">
                <div class="flex items-center space-x-2 mb-2">
                    <span class="flex h-2.5 w-2.5 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                    </span>
                    <span class="text-xs font-semibold text-emerald-400 uppercase tracking-wider">Mode Admin Aktif</span>
                </div>
                <div class="text-sm space-y-1 text-slate-300 font-medium">
                    <p class="flex justify-between gap-4"><span class="text-slate-400">Total Orang Tua:</span> <span>{{ count($parents) }} Akun</span></p>
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
                        <div class="text-center py-8 text-slate-400"><i data-lucide="loader" class="animate-spin w-8 h-8 mx-auto mb-2 text-emerald-500"></i> Memuat data...</div>
                    </div>
                </div>

                <!-- Card 2: Balita Terpilih Detail -->
                <div id="active-child-card" class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-100 shadow-sm relative overflow-hidden transition-all duration-300 min-h-[300px]">
                    <!-- Placeholder when no child selected -->
                    <div id="active-child-placeholder" class="hidden absolute inset-0 bg-white flex flex-col items-center justify-center p-6 text-center">
                        <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mb-4"><i data-lucide="user-x" class="text-slate-400 w-8 h-8"></i></div>
                        <h3 class="text-slate-700 font-semibold mb-1">Pilih Balita</h3>
                        <p class="text-slate-400 text-xs max-w-[200px]">Silakan pilih nama balita dari daftar di atas untuk melakukan pengelolaan data.</p>
                    </div>

                    <!-- Actual content loaded by JS -->
                    <div class="space-y-5">
                        <div class="flex items-start justify-between">
                            <div class="space-y-1">
                                <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 text-[10px] font-bold uppercase tracking-wider rounded">Profil Balita</span>
                                <h3 id="active-child-nama" class="text-xl font-bold text-slate-800 leading-tight">Nama Balita</h3>
                                <p id="active-child-ibu" class="text-xs text-slate-400 font-medium">Ibu: Nama Ibu</p>
                                <p id="active-child-parent" class="text-[10px] text-emerald-600 font-bold bg-emerald-50 inline-block px-1.5 py-0.5 rounded mt-1"></p>
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

                        <div class="space-y-2">
                            <button onclick="openModal('add-record-modal')" class="w-full flex items-center justify-center space-x-2 py-3 bg-slate-900 hover:bg-slate-800 text-white font-semibold text-sm rounded-2xl transition-all duration-300">
                                <i data-lucide="clipboard-plus" class="w-4 h-4"></i>
                                <span>Catat Hasil Periksa Baru</span>
                            </button>
                            
                            <div class="grid grid-cols-2 gap-2">
                                <button onclick="openEditChildModal()" class="flex items-center justify-center space-x-1.5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition-all duration-300">
                                    <i data-lucide="edit" class="w-3.5 h-3.5"></i>
                                    <span>Edit Profil</span>
                                </button>
                                <button onclick="confirmDeleteChild()" class="flex items-center justify-center space-x-1.5 py-2.5 bg-red-50 hover:bg-red-100 text-red-600 font-bold text-xs rounded-xl transition-all duration-300">
                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                    <span>Hapus Balita</span>
                                </button>
                            </div>
                        </div>
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
                                <span>Data Tumbuh Kembang (KMS) & Riwayat</span>
                            </h2>
                            <p class="text-xs text-slate-400 mt-1">Kelola data pertumbuhan bulanan dan riwayat KMS secara dinamis.</p>
                        </div>
                        
                        <!-- Toggle Button Chart VS Data -->
                        <div class="flex bg-slate-100 p-1 rounded-xl self-stretch sm:self-auto">
                            <button id="tab-chart-btn" onclick="switchTab('chart')" class="flex-1 sm:flex-initial px-4 py-2 text-xs font-bold rounded-lg transition-all duration-300 bg-white text-emerald-700 shadow-sm">
                                <i data-lucide="line-chart" class="inline w-3.5 h-3.5 mr-1 mb-0.5"></i>Grafik KMS
                            </button>
                            <button id="tab-data-btn" onclick="switchTab('data')" class="flex-1 sm:flex-initial px-4 py-2 text-xs font-bold rounded-lg transition-all duration-300 text-slate-500 hover:text-slate-700">
                                <i data-lucide="database" class="inline w-3.5 h-3.5 mr-1 mb-0.5"></i>Kelola Riwayat (CRUD)
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

                    <!-- Panel 2: Table Data (With full Delete CRUD support) -->
                    <div id="panel-data-container" class="hidden overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-slate-100 text-slate-400 text-xs font-bold uppercase tracking-wider">
                                    <th class="py-3.5 px-4">Bulan Ke</th>
                                    <th class="py-3.5 px-4">Tgl Periksa</th>
                                    <th class="py-3.5 px-4 text-center">Berat (kg)</th>
                                    <th class="py-3.5 px-4 text-center">Tinggi (cm)</th>
                                    <th class="py-3.5 px-4 text-center">Status Gizi</th>
                                    <th class="py-3.5 px-4 text-right">Aksi</th>
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
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-500 border-t border-slate-800 mt-12 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-4">
            <div class="flex items-center justify-center space-x-2">
                <div class="w-8 h-8 bg-emerald-600 rounded-lg flex items-center justify-center text-white"><i data-lucide="shield-check" class="w-4.5 h-4.5"></i></div>
                <span class="text-white font-extrabold text-base tracking-wider uppercase">SINDU ADMIN</span>
            </div>
            <p class="text-[10px] text-slate-600">&copy; 2026 SINDU (Sistem Posyandu Digital). All rights reserved.</p>
        </div>
    </footer>

    <!-- MODAL 1: Tambah Anak Baru -->
    <div id="add-child-modal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl relative border border-slate-100">
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

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Hubungkan dengan Akun Orang Tua (User)</label>
                    <select name="user_id" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl outline-none focus:border-emerald-500 focus:bg-white text-sm font-semibold transition-all duration-300">
                        <option value="">-- Tanpa Akun (Mandiri) --</option>
                        @foreach($parents as $parent)
                            <option value="{{ $parent->id }}">{{ $parent->name }} ({{ $parent->email }})</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="w-full flex items-center justify-center space-x-2 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-2xl shadow-md transition-all duration-300">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    <span>Daftarkan Balita</span>
                </button>
            </form>
        </div>
    </div>

    <!-- MODAL 2: Edit Profil Balita (Update Child CRUD) -->
    <div id="edit-child-modal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl relative border border-slate-100">
            <button onclick="closeModal('edit-child-modal')" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600"><i data-lucide="x" class="w-6 h-6"></i></button>
            
            <h3 class="text-lg font-bold text-slate-800 flex items-center space-x-2 mb-4">
                <i data-lucide="edit-3" class="text-emerald-600 w-5 h-5"></i>
                <span>Edit Profil Balita</span>
            </h3>

            <form id="edit-child-form" onsubmit="submitEditChild(event)" class="space-y-4">
                @csrf
                <input type="hidden" name="id" id="edit-child-id">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Balita</label>
                    <input type="text" name="nama" id="edit-child-nama" required placeholder="Nama Lengkap Balita" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl outline-none focus:border-emerald-500 focus:bg-white text-sm font-semibold transition-all duration-300">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Jenis Kelamin</label>
                        <select name="jenis_kelamin" id="edit-child-gender" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl outline-none focus:border-emerald-500 focus:bg-white text-sm font-semibold transition-all duration-300">
                            <option value="L">Laki-Laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" id="edit-child-lahir" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl outline-none focus:border-emerald-500 focus:bg-white text-sm font-semibold transition-all duration-300">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Ibu Kandung</label>
                    <input type="text" name="nama_ibu" id="edit-child-ibu" required placeholder="Nama Ibu Kandung" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl outline-none focus:border-emerald-500 focus:bg-white text-sm font-semibold transition-all duration-300">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Hubungkan dengan Akun Orang Tua (User)</label>
                    <select name="user_id" id="edit-child-parent-select" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl outline-none focus:border-emerald-500 focus:bg-white text-sm font-semibold transition-all duration-300">
                        <option value="">-- Tanpa Akun (Mandiri) --</option>
                        @foreach($parents as $parent)
                            <option value="{{ $parent->id }}">{{ $parent->name }} ({{ $parent->email }})</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="w-full flex items-center justify-center space-x-2 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-2xl shadow-md transition-all duration-300">
                    <i data-lucide="check" class="w-4 h-4"></i>
                    <span>Simpan Perubahan</span>
                </button>
            </form>
        </div>
    </div>

    <!-- MODAL 3: Tambah Catatan Pemeriksaan -->
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

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Keluhan Anak (Opsional)</label>
                    <textarea name="keluhan" rows="2" placeholder="Contoh: Kurang nafsu makan, demam ringan, batuk" class="w-full px-4 py-2 bg-slate-50 border border-slate-100 rounded-xl outline-none focus:border-emerald-500 focus:bg-white text-sm font-semibold transition-all duration-300"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Solusi / Saran yang Disarankan (Opsional)</label>
                    <textarea name="solusi" rows="2" placeholder="Contoh: Berikan PMT Pemulihan, berikan ASI eksklusif, rujuk ke Puskesmas" class="w-full px-4 py-2 bg-slate-50 border border-slate-100 rounded-xl outline-none focus:border-emerald-500 focus:bg-white text-sm font-semibold transition-all duration-300"></textarea>
                </div>

                <button type="submit" class="w-full flex items-center justify-center space-x-2 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-2xl shadow-md transition-all duration-300">
                    <i data-lucide="check" class="w-4 h-4"></i>
                    <span>Simpan Pemeriksaan</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Toast Notification Success System -->
    <div id="toast-success" class="fixed top-24 right-4 z-50 p-4 bg-white border border-emerald-100 text-slate-700 text-sm font-semibold rounded-2xl shadow-xl flex items-center gap-3 hidden">
        <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0"><i data-lucide="check-circle" class="w-5 h-5"></i></div>
        <div>
            <span class="block font-bold text-slate-800">Berhasil!</span>
            <span id="toast-message" class="text-xs text-slate-500">Aksi berhasil diselesaikan.</span>
        </div>
        <button onclick="document.getElementById('toast-success').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 ml-2"><i data-lucide="x" class="w-4 h-4"></i></button>
    </div>

    <!-- Scripting (Vanilla JS for ultra responsiveness) -->
    <script>
        let childrenData = [];
        let activeChild = null;
        let chart = null;
        let activeTab = 'chart';

        // Parents list mapping for quick label display
        const parentMap = {
            @foreach($parents as $parent)
                "{{ $parent->id }}": "{{ $parent->name }}",
            @endforeach
        };

        document.addEventListener('DOMContentLoaded', () => {
            fetchChildren();
            
            // Set date inputs to today by default
            const today = new Date().toISOString().split('T')[0];
            document.querySelectorAll('input[type="date"]').forEach(el => el.value = today);

            lucide.createIcons();
        });

        // Show Toast Utility
        function showToast(message) {
            const toast = document.getElementById('toast-success');
            document.getElementById('toast-message').innerText = message;
            toast.classList.remove('hidden');
            setTimeout(() => {
                toast.classList.add('hidden');
            }, 4000);
        }

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

        // Switch tabs
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
                    setTimeout(() => renderKMSChart(), 50);
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
                item.className = `child-item flex items-center justify-between p-3.5 rounded-2xl cursor-pointer border hover:border-emerald-500 hover:bg-slate-50/50 transition-all duration-300 ${isSelected ? 'border-emerald-500 bg-emerald-50/30 font-bold' : 'border-slate-100/50'}`;
                item.onclick = () => selectChild(child.id);
                item.innerHTML = `
                    <div class="flex items-center space-x-3 min-w-0">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center font-bold text-xs shrink-0 ${child.jenis_kelamin === 'L' ? 'bg-blue-50 text-blue-600' : 'bg-pink-50 text-pink-600'}">
                            ${child.jenis_kelamin}
                        </div>
                        <div class="min-w-0">
                            <span class="block text-slate-700 text-sm truncate leading-tight">${child.nama}</span>
                            <span class="text-[10px] text-slate-400 font-medium">${ageText} • Ibu: ${child.nama_ibu || '-'}</span>
                        </div>
                    </div>
                    <span class="px-2.5 py-1 text-[10px] font-bold rounded-lg shrink-0 ${statusClass}">${status}</span>
                `;
                container.appendChild(item);
            });

            if (childrenData.length > 0) {
                if (selectId) {
                    selectChild(selectId);
                } else if (!activeChild) {
                    selectChild(childrenData[0].id);
                } else {
                    selectChild(activeChild.id);
                }
            }
        }

        // Filter children
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

        // Select a child
        function selectChild(id) {
            const child = childrenData.find(c => c.id === id);
            if (!child) return;
            activeChild = child;
            
            const items = document.querySelectorAll('.child-item');
            childrenData.forEach((c, idx) => {
                if (items[idx]) {
                    if (c.id === id) {
                        items[idx].classList.add('border-emerald-500', 'bg-emerald-50/30', 'font-bold');
                    } else {
                        items[idx].classList.remove('border-emerald-500', 'bg-emerald-50/30', 'font-bold');
                    }
                }
            });

            setActiveChild(child);
        }

        // Populate active child
        function setActiveChild(child) {
            const cardPlaceholder = document.getElementById('active-child-placeholder');
            if (!child) {
                cardPlaceholder.classList.remove('hidden');
                return;
            }
            cardPlaceholder.classList.add('hidden');

            document.getElementById('active-child-nama').innerText = child.nama;
            document.getElementById('active-child-ibu').innerText = `Ibu: ${child.nama_ibu || '-'}`;
            
            const parentLabel = document.getElementById('active-child-parent');
            if (child.user_id && parentMap[child.user_id]) {
                parentLabel.innerText = `Orang Tua: ${parentMap[child.user_id]}`;
                parentLabel.classList.remove('hidden');
            } else {
                parentLabel.classList.add('hidden');
            }

            const genderIcon = document.getElementById('active-child-gender-icon');
            genderIcon.innerText = child.jenis_kelamin;
            if (child.jenis_kelamin === 'L') {
                genderIcon.className = "w-10 h-10 rounded-xl flex items-center justify-center bg-blue-50 text-blue-500 font-bold text-sm shrink-0";
            } else {
                genderIcon.className = "w-10 h-10 rounded-xl flex items-center justify-center bg-pink-50 text-pink-500 font-bold text-sm shrink-0";
            }

            const totalRecords = child.records.length;
            const lastRecord = totalRecords > 0 ? child.records[totalRecords - 1] : null;

            const dob = new Date(child.tanggal_lahir);
            document.getElementById('active-child-lahir').innerText = dob.toLocaleDateString('id-ID', {day: '2-digit', month: '2-digit', year: 'numeric'});
            
            const ageMonths = lastRecord ? lastRecord.umur_bulan : calculateAgeInMonths(dob);
            document.getElementById('active-child-usia').innerText = `${ageMonths} Bulan`;

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

            document.getElementById('metric-berat').innerHTML = lastRecord ? `${lastRecord.berat_badan} <span class="text-sm font-bold text-slate-400">kg</span>` : `- <span class="text-sm font-bold text-slate-400">kg</span>`;
            document.getElementById('metric-tinggi').innerHTML = lastRecord ? `${lastRecord.tinggi_badan} <span class="text-sm font-bold text-slate-400">cm</span>` : `- <span class="text-sm font-bold text-slate-400">cm</span>`;

            document.getElementById('modal-record-child-id').value = child.id;
            document.getElementById('modal-record-child-nama').value = child.nama;

            if (activeTab === 'chart') {
                renderKMSChart();
            } else {
                renderHistoryTable();
            }
        }

        // Render KMS Growth Chart (ApexCharts)
        function renderKMSChart() {
            if (!activeChild) return;

            const chartLoader = document.getElementById('chart-loader');
            chartLoader.classList.remove('hidden');

            const records = activeChild.records;
            const sex = activeChild.jenis_kelamin;
            const maxAge = Math.max(24, records.length > 0 ? Math.max(...records.map(r => r.umur_bulan)) : 0);
            
            const ages = [];
            for (let i = 0; i <= maxAge; i++) ages.push(i);

            const standardCurves = {
                'L': {
                    median: [3.3, 4.5, 5.6, 6.4, 7.0, 7.5, 7.9, 8.3, 8.6, 8.9, 9.2, 9.4, 9.6, 9.9, 10.1, 10.3, 10.5, 10.7, 10.9, 11.1, 11.3, 11.5, 11.8, 12.0, 12.2],
                    sd2: [3.9, 5.1, 6.3, 7.2, 7.8, 8.4, 8.8, 9.2, 9.6, 9.9, 10.2, 10.5, 10.8, 11.0, 11.3, 11.5, 11.7, 12.0, 12.2, 12.5, 12.7, 12.9, 13.2, 13.4, 13.6],
                    sdMinus2: [2.9, 3.9, 4.9, 5.7, 6.2, 6.7, 7.1, 7.4, 7.7, 8.0, 8.2, 8.4, 8.6, 8.8, 9.0, 9.2, 9.4, 9.5, 9.7, 9.9, 10.1, 10.2, 10.4, 10.5, 10.7],
                    sdMinus3: [2.5, 3.4, 4.3, 5.0, 5.5, 6.0, 6.4, 6.7, 7.0, 7.2, 7.4, 7.6, 7.8, 8.0, 8.2, 8.3, 8.5, 8.7, 8.8, 9.0, 9.2, 9.3, 9.5, 9.6, 9.7]
                },
                'P': {
                    median: [3.2, 4.2, 5.1, 5.8, 6.4, 6.9, 7.3, 7.6, 7.9, 8.2, 8.5, 8.7, 8.9, 9.2, 9.4, 9.6, 9.8, 10.0, 10.2, 10.4, 10.6, 10.9, 11.1, 11.3, 11.5],
                    sd2: [3.7, 4.8, 5.8, 6.6, 7.2, 7.8, 8.2, 8.6, 9.0, 9.3, 9.6, 9.9, 10.2, 10.4, 10.7, 10.9, 11.1, 11.4, 11.6, 11.8, 12.0, 12.3, 12.5, 12.7, 13.0],
                    sdMinus2: [2.8, 3.7, 4.5, 5.0, 5.5, 6.0, 6.4, 6.7, 7.0, 7.2, 7.5, 7.7, 7.9, 8.1, 8.3, 8.5, 8.7, 8.9, 9.0, 9.2, 9.4, 9.6, 9.8, 9.9, 10.1],
                    sdMinus3: [2.4, 3.2, 3.9, 4.5, 5.0, 5.4, 5.7, 6.0, 6.3, 6.5, 6.7, 6.9, 7.1, 7.3, 7.5, 7.7, 7.8, 8.0, 8.2, 8.3, 8.5, 8.7, 8.8, 9.0, 9.1]
                }
            };

            const curves = standardCurves[sex] || standardCurves['L'];
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
                    const slopeMedian = (curves.median[24] - curves.median[0]) / 24;
                    finalMedian.push(parseFloat((curves.median[24] + (i - 24) * slopeMedian).toFixed(1)));
                    finalSd2.push(parseFloat((curves.sd2[24] + (i - 24) * slopeMedian).toFixed(1)));
                    finalSdMinus2.push(parseFloat((curves.sdMinus2[24] + (i - 24) * slopeMedian).toFixed(1)));
                    finalSdMinus3.push(parseFloat((curves.sdMinus3[24] + (i - 24) * slopeMedian).toFixed(1)));
                }
            }

            const childWeights = ages.map(age => {
                const rec = records.find(r => r.umur_bulan === age);
                return rec ? rec.berat_badan : null;
            });

            const options = {
                series: [
                    { name: 'Berat Badan Anak (kg)', type: 'line', data: childWeights },
                    { name: 'WHO Batas Atas (+2 SD)', type: 'line', data: finalSd2 },
                    { name: 'WHO Median', type: 'line', data: finalMedian },
                    { name: 'WHO Batas Bawah (-2 SD)', type: 'line', data: finalSdMinus2 },
                    { name: 'WHO Gizi Buruk (-3 SD)', type: 'line', data: finalSdMinus3 }
                ],
                chart: {
                    height: 350,
                    type: 'line',
                    toolbar: { show: false },
                    zoom: { enabled: false }
                },
                colors: ['#0f172a', '#e11d48', '#10b981', '#f59e0b', '#dc2626'],
                stroke: {
                    width: [4, 1.5, 2, 1.5, 1.5],
                    curve: 'smooth',
                    dashArray: [0, 5, 0, 5, 8]
                },
                markers: {
                    size: [5, 0, 0, 0, 0],
                    colors: ['#0f172a'],
                    strokeWidth: 2,
                    hover: { size: 7 }
                },
                xaxis: {
                    categories: ages,
                    title: { text: 'Usia (Bulan)', style: { fontWeight: 700 } }
                },
                yaxis: {
                    title: { text: 'Berat Badan (kg)', style: { fontWeight: 700 } },
                    min: 0
                },
                legend: { position: 'top', horizontalAlign: 'center' },
                grid: { borderColor: '#f1f5f9' },
                tooltip: { shared: true, intersect: false }
            };

            if (chart) {
                chart.destroy();
            }

            chart = new ApexCharts(document.querySelector("#kms-chart"), options);
            chart.render();
            chartLoader.classList.add('hidden');
        }

        // Render History Table (Read & Delete Growth Records CRUD)
        function renderHistoryTable() {
            const tbody = document.getElementById('history-table-body');
            const empty = document.getElementById('history-table-empty');
            tbody.innerHTML = '';

            const records = [...activeChild.records].reverse();

            if (records.length === 0) {
                empty.classList.remove('hidden');
                return;
            }

            empty.classList.add('hidden');
            records.forEach(rec => {
                const date = new Date(rec.tanggal_periksa).toLocaleDateString('id-ID', {day: '2-digit', month: 'short', year: 'numeric'});
                
                let statusClass = 'bg-slate-100 text-slate-700';
                if (rec.status_gizi === 'Gizi Baik') statusClass = 'bg-emerald-100 text-emerald-800 font-bold';
                else if (rec.status_gizi === 'Gizi Kurang') statusClass = 'bg-orange-100 text-orange-800 font-bold';
                else if (rec.status_gizi === 'Gizi Buruk') statusClass = 'bg-red-100 text-red-800 font-bold';
                else if (rec.status_gizi === 'Gizi Lebih') statusClass = 'bg-amber-100 text-amber-800 font-bold';

                const row = document.createElement('tr');
                row.className = "hover:bg-slate-50/50 transition-colors duration-200 border-b border-slate-50";
                row.innerHTML = `
                    <td class="py-3.5 px-4 font-bold text-slate-700">Bulan ke-${rec.umur_bulan}</td>
                    <td class="py-3.5 px-4 text-slate-500 font-semibold">${date}</td>
                    <td class="py-3.5 px-4 text-center font-extrabold text-slate-700">${rec.berat_badan} kg</td>
                    <td class="py-3.5 px-4 text-center font-extrabold text-slate-700">${rec.tinggi_badan} cm</td>
                    <td class="py-3.5 px-4 text-center"><span class="px-2 py-0.5 rounded-md text-[10px] ${statusClass}">${rec.status_gizi}</span></td>
                    <td class="py-3.5 px-4 text-right">
                        <button onclick="deleteRecord(${rec.id})" class="p-1.5 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors" title="Hapus pemeriksaan ini">
                            <i data-lucide="trash" class="w-4 h-4"></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(row);

                if (rec.keluhan || rec.solusi) {
                    const complaintRow = document.createElement('tr');
                    complaintRow.className = "bg-slate-50/30 border-b border-slate-100/50";
                    complaintRow.innerHTML = `
                        <td colspan="6" class="px-6 py-2">
                            <div class="flex flex-col sm:flex-row gap-3">
                                ${rec.keluhan ? `
                                <div class="flex-1 bg-amber-50/80 border border-amber-100/50 p-2.5 rounded-xl">
                                    <span class="font-extrabold text-[10px] text-amber-800 uppercase tracking-wider flex items-center gap-1 mb-1">
                                        <i data-lucide="message-square-warning" class="w-3.5 h-3.5"></i> Keluhan Anak
                                    </span> 
                                    <p class="text-xs text-slate-700 font-semibold leading-relaxed">${rec.keluhan}</p>
                                </div>` : ''}
                                
                                ${rec.solusi ? `
                                <div class="flex-1 bg-emerald-50/80 border border-emerald-100/50 p-2.5 rounded-xl">
                                    <span class="font-extrabold text-[10px] text-emerald-800 uppercase tracking-wider flex items-center gap-1 mb-1">
                                        <i data-lucide="sparkles" class="w-3.5 h-3.5"></i> Solusi / Saran Kader
                                    </span> 
                                    <p class="text-xs text-slate-700 font-semibold leading-relaxed">${rec.solusi}</p>
                                </div>` : ''}
                            </div>
                        </td>
                    `;
                    tbody.appendChild(complaintRow);
                }
            });

            lucide.createIcons();
        }

        // Global stats update
        function updateGlobalStats() {
            const total = childrenData.length;
            document.getElementById('header-total-anak').innerText = `${total} Anak`;

            let giziBaik = 0;
            childrenData.forEach(c => {
                if (c.records.length > 0) {
                    const last = c.records[c.records.length - 1];
                    if (last.status_gizi === 'Gizi Baik') giziBaik++;
                }
            });
            document.getElementById('header-gizi-baik').innerText = `${giziBaik} Anak`;
        }

        // Open edit child modal with values (Update Child CRUD)
        function openEditChildModal() {
            if (!activeChild) return;
            document.getElementById('edit-child-id').value = activeChild.id;
            document.getElementById('edit-child-nama').value = activeChild.nama;
            document.getElementById('edit-child-gender').value = activeChild.jenis_kelamin;
            document.getElementById('edit-child-lahir').value = activeChild.tanggal_lahir;
            document.getElementById('edit-child-ibu').value = activeChild.nama_ibu;
            document.getElementById('edit-child-parent-select').value = activeChild.user_id || "";
            openModal('edit-child-modal');
        }

        // Submit edit child via API (Update Child CRUD)
        function submitEditChild(e) {
            e.preventDefault();
            const form = document.getElementById('edit-child-form');
            const formData = new FormData(form);
            const id = document.getElementById('edit-child-id').value;

            // Convert form data to JSON
            const data = {};
            formData.forEach((value, key) => data[key] = value);

            fetch(`/api/children/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    closeModal('edit-child-modal');
                    showToast(res.message);
                    fetchChildren(id);
                }
            })
            .catch(err => {
                console.error(err);
            });
        }

        // Confirm and delete active child completely (Delete Child CRUD)
        function confirmDeleteChild() {
            if (!activeChild) return;
            const confirmName = prompt(`⚠️ PERINGATAN: Menghapus data ini akan menghapus semua riwayat tumbuh kembang anak secara permanen.\n\nKetik nama balita "${activeChild.nama}" untuk mengonfirmasi penghapusan:`);
            
            if (confirmName === activeChild.nama) {
                fetch(`/api/children/${activeChild.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        showToast(res.message);
                        activeChild = null;
                        fetchChildren();
                    }
                })
                .catch(err => console.error(err));
            } else if (confirmName !== null) {
                alert("Konfirmasi nama tidak sesuai. Penghapusan dibatalkan.");
            }
        }

        // Delete growth record (Delete Record CRUD)
        function deleteRecord(recordId) {
            if (!confirm("Apakah Anda yakin ingin menghapus catatan pemeriksaan bulanan ini?")) return;

            fetch(`/api/records/${recordId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    showToast(res.message);
                    fetchChildren(activeChild.id);
                }
            })
            .catch(err => console.error(err));
        }

        // Submit new child (Create Child CRUD)
        function submitChild(e) {
            e.preventDefault();
            const form = document.getElementById('add-child-form');
            const formData = new FormData(form);

            fetch('/api/children', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    closeModal('add-child-modal');
                    form.reset();
                    showToast(res.message);
                    fetchChildren(res.child.id);
                }
            })
            .catch(err => {
                console.error(err);
            });
        }

        // Submit new growth record (Create Record CRUD)
        function submitRecord(e) {
            e.preventDefault();
            const form = document.getElementById('add-record-form');
            const formData = new FormData(form);

            fetch('/api/records', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    closeModal('add-record-modal');
                    form.reset();
                    showToast(res.message);
                    fetchChildren(activeChild.id);
                } else {
                    alert(res.message);
                }
            })
            .catch(err => {
                console.error(err);
            });
        }

        // Modals opening/closing
        function openModal(id) {
            const modal = document.getElementById(id);
            modal.classList.remove('hidden');
            
            // Set date inside modals if adding record
            if (id === 'add-record-modal' && activeChild) {
                const totalRecords = activeChild.records.length;
                const nextMonth = totalRecords > 0 ? activeChild.records[totalRecords - 1].umur_bulan + 1 : calculateAgeInMonths(new Date(activeChild.tanggal_lahir));
                modal.querySelector('input[name="umur_bulan"]').value = nextMonth;
            }
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }

        // Age calculation helpers
        function calculateAgeInMonths(dob) {
            const today = new Date();
            let months = (today.getFullYear() - dob.getFullYear()) * 12;
            months -= dob.getMonth();
            months += today.getMonth();
            return Math.max(0, months);
        }
    </script>
</body>
</html>
