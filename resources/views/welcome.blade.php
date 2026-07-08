<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KLIIN — Smart Laundry Management Platform</title>
    <meta name="description" content="Platform manajemen laundry cerdas dengan POS, IoT, AI, dan WhatsApp Bot otomatis untuk UMKM laundry Indonesia.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body { font-family: 'Outfit', sans-serif; }</style>
</head>
<body class="h-full antialiased bg-white text-[#1A1D23]">
    
    <!-- Navbar -->
    <nav class="border-b border-[#E2E7EF] bg-white/80 backdrop-blur-xl fixed top-0 left-0 right-0 z-50">
        <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <div class="h-8 w-8 rounded-lg bg-gradient-to-br from-[#1E3A5F] to-[#2A5082] flex items-center justify-center">
                    <svg class="h-4 w-4 text-[#D4A853]" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 14a6 6 0 110-12 6 6 0 010 12z"/><path d="M10 5a1 1 0 011 1v3.586l2.707 2.707a1 1 0 01-1.414 1.414l-3-3A1 1 0 019 10V6a1 1 0 011-1z"/></svg>
                </div>
                <span class="text-lg font-bold text-[#1E3A5F] tracking-wider">KLIIN</span>
            </div>
            <div class="flex items-center space-x-6">
                <a href="#features" class="text-sm text-[#4A5568] hover:text-[#1E3A5F] transition-colors hidden sm:block">Fitur</a>
                <a href="#pricing" class="text-sm text-[#4A5568] hover:text-[#1E3A5F] transition-colors hidden sm:block">Harga</a>
                <a href="{{ route('login') }}" class="text-sm font-semibold text-[#1E3A5F] hover:text-[#2A5082] transition-colors">
                    Masuk
                </a>
                <a href="{{ route('register') }}" class="px-5 py-2 bg-[#1E3A5F] hover:bg-[#2A5082] text-white rounded-xl text-xs font-bold shadow-lg shadow-[#1E3A5F]/10 transition-all">
                    Daftar Gratis
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="pt-32 pb-20 px-6 max-w-5xl mx-auto text-center space-y-8 relative">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_60%_50%_at_50%_0%,rgba(30,58,95,0.04),transparent)]"></div>
        <div class="relative z-10">
            <span class="inline-flex items-center px-4 py-1.5 bg-[#1E3A5F]/5 text-[#1E3A5F] border border-[#1E3A5F]/10 rounded-full text-xs font-semibold tracking-wider uppercase">
                <span class="h-1.5 w-1.5 rounded-full bg-[#D4A853] mr-2 animate-pulse"></span>
                Platform Laundry Cerdas #1 Indonesia
            </span>
            <h1 class="text-4xl md:text-6xl font-extrabold text-[#1A1D23] tracking-tight leading-tight max-w-4xl mx-auto mt-6">
                Kelola Bisnis Laundry dengan <span class="bg-clip-text text-transparent bg-gradient-to-r from-[#1E3A5F] via-[#2A5082] to-[#D4A853]">Teknologi Terdepan</span>
            </h1>
            <p class="text-base md:text-lg text-[#4A5568] max-w-2xl mx-auto leading-relaxed mt-6">
                Multi-tenant cloud platform dengan POS Kasir, Timbangan IoT, Klasifikasi AI, QR-Rack Locator, WhatsApp Bot Otomatis, dan Laporan Keuangan lengkap.
            </p>

            <!-- Tracking Widget -->
            <div class="max-w-md mx-auto bg-white border border-[#E2E7EF] p-5 rounded-2xl shadow-xl shadow-[#1E3A5F]/5 space-y-4 mt-10">
                <h3 class="text-sm font-bold text-[#1A1D23] text-left flex items-center space-x-2">
                    <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Lacak Cucian Anda</span>
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <input id="trackSubdomain" type="text" placeholder="Subdomain (cth: demo)"
                        class="px-3 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] placeholder-[#8896A6] rounded-xl text-xs focus:outline-none focus:border-[#1E3A5F] focus:ring-2 focus:ring-[#1E3A5F]/10 transition-all">
                    <input id="trackInvoice" type="text" placeholder="No Invoice (cth: INV-260630...)"
                        class="px-3 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] placeholder-[#8896A6] rounded-xl text-xs focus:outline-none focus:border-[#1E3A5F] focus:ring-2 focus:ring-[#1E3A5F]/10 transition-all">
                </div>
                <button onclick="lacakCucian()" 
                    class="w-full py-2.5 bg-gradient-to-r from-[#1E3A5F] to-[#2A5082] hover:from-[#2A5082] hover:to-[#1E3A5F] text-white rounded-xl text-xs font-bold transition-all shadow-lg shadow-[#1E3A5F]/10 cursor-pointer">
                    Lacak Status Cucian →
                </button>
            </div>
        </div>
    </header>

    <!-- Features Section -->
    <section id="features" class="py-20 border-t border-[#E2E7EF] bg-[#F8F9FC]">
        <div class="max-w-7xl mx-auto px-6 space-y-12">
            <div class="text-center space-y-4">
                <h2 class="text-3xl font-extrabold text-[#1A1D23]">Fitur Unggulan</h2>
                <p class="text-sm text-[#4A5568] max-w-lg mx-auto">Teknologi tinggi yang memecahkan masalah operasional terbesar toko laundry Anda.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Feature 1 -->
                <div class="bg-white border border-[#E2E7EF] p-6 rounded-2xl space-y-4 hover:shadow-xl hover:shadow-[#1E3A5F]/5 hover:border-[#D4A853]/30 transition-all group">
                    <div class="p-3 bg-[#1E3A5F]/5 text-[#1E3A5F] border border-[#1E3A5F]/10 rounded-xl w-fit group-hover:bg-[#D4A853]/10 group-hover:text-[#D4A853] group-hover:border-[#D4A853]/20 transition-all">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
                    </div>
                    <h3 class="text-base font-bold text-[#1A1D23]">Timbangan IoT Bluetooth</h3>
                    <p class="text-xs text-[#4A5568] leading-relaxed">POS membaca berat cucian langsung dari timbangan digital via Bluetooth. Anti-kecurangan kasir.</p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-white border border-[#E2E7EF] p-6 rounded-2xl space-y-4 hover:shadow-xl hover:shadow-[#1E3A5F]/5 hover:border-[#D4A853]/30 transition-all group">
                    <div class="p-3 bg-[#1E3A5F]/5 text-[#1E3A5F] border border-[#1E3A5F]/10 rounded-xl w-fit group-hover:bg-[#D4A853]/10 group-hover:text-[#D4A853] group-hover:border-[#D4A853]/20 transition-all">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <h3 class="text-base font-bold text-[#1A1D23]">AI Clothes Classifier</h3>
                    <p class="text-xs text-[#4A5568] leading-relaxed">Foto tumpukan baju otomatis dipindai AI untuk mendeteksi jumlah dan jenis pakaian secara instan.</p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-white border border-[#E2E7EF] p-6 rounded-2xl space-y-4 hover:shadow-xl hover:shadow-[#1E3A5F]/5 hover:border-[#D4A853]/30 transition-all group">
                    <div class="p-3 bg-[#1E3A5F]/5 text-[#1E3A5F] border border-[#1E3A5F]/10 rounded-xl w-fit group-hover:bg-[#D4A853]/10 group-hover:text-[#D4A853] group-hover:border-[#D4A853]/20 transition-all">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                    </div>
                    <h3 class="text-base font-bold text-[#1A1D23]">QR-Rack Locator</h3>
                    <p class="text-xs text-[#4A5568] leading-relaxed">Lacak posisi pakaian di rak penyimpanan melalui scan QR code. Hemat waktu staf.</p>
                </div>

                <!-- Feature 4 -->
                <div class="bg-white border border-[#E2E7EF] p-6 rounded-2xl space-y-4 hover:shadow-xl hover:shadow-[#1E3A5F]/5 hover:border-[#D4A853]/30 transition-all group">
                    <div class="p-3 bg-[#1E3A5F]/5 text-[#1E3A5F] border border-[#1E3A5F]/10 rounded-xl w-fit group-hover:bg-[#D4A853]/10 group-hover:text-[#D4A853] group-hover:border-[#D4A853]/20 transition-all">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    </div>
                    <h3 class="text-base font-bold text-[#1A1D23]">WhatsApp Notification</h3>
                    <p class="text-xs text-[#4A5568] leading-relaxed">Notifikasi status cucian otomatis dikirim ke WhatsApp pelanggan dengan tombol tracking.</p>
                </div>
            </div>

            <!-- Additional Features Row -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white border border-[#E2E7EF] p-6 rounded-2xl space-y-3 hover:shadow-lg transition-all">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-emerald-50 text-emerald-600 rounded-lg"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg></div>
                        <h4 class="text-sm font-bold text-[#1A1D23]">Laporan Keuangan</h4>
                    </div>
                    <p class="text-xs text-[#4A5568]">Laba-rugi, rekap harian/bulanan, top services, dan analisis pelanggan.</p>
                </div>
                <div class="bg-white border border-[#E2E7EF] p-6 rounded-2xl space-y-3 hover:shadow-lg transition-all">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-amber-50 text-amber-600 rounded-lg"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg></div>
                        <h4 class="text-sm font-bold text-[#1A1D23]">Manajemen Pelanggan & Loyalty</h4>
                    </div>
                    <p class="text-xs text-[#4A5568]">CRUD pelanggan, membership tier, poin loyalty, dan riwayat transaksi.</p>
                </div>
                <div class="bg-white border border-[#E2E7EF] p-6 rounded-2xl space-y-3 hover:shadow-lg transition-all">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-blue-50 text-blue-600 rounded-lg"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg></div>
                        <h4 class="text-sm font-bold text-[#1A1D23]">Inventaris & Staf</h4>
                    </div>
                    <p class="text-xs text-[#4A5568]">Stok bahan baku, alert low stock, CRUD staf, absensi, dan shift management.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="py-20 max-w-5xl mx-auto px-6 space-y-12">
        <div class="text-center space-y-4">
            <h2 class="text-3xl font-extrabold text-[#1A1D23]">Paket Langganan</h2>
            <p class="text-sm text-[#4A5568]">Pilih paket terbaik untuk ekspansi bisnis laundry Anda.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Plan 1 -->
            <div class="bg-white border border-[#E2E7EF] p-8 rounded-2xl space-y-6 flex flex-col justify-between hover:shadow-xl hover:shadow-[#1E3A5F]/5 transition-all">
                <div class="space-y-4">
                    <div>
                        <h4 class="text-md font-bold text-[#1A1D23]">Starter</h4>
                        <p class="text-[11px] text-[#8896A6]">Untuk outlet laundry pemula</p>
                    </div>
                    <p class="text-3xl font-extrabold text-[#1A1D23]">Rp 150.000 <span class="text-xs text-[#8896A6] font-medium">/bln</span></p>
                    <ul class="space-y-2 text-xs text-[#4A5568]">
                        <li class="flex items-center"><svg class="h-4 w-4 text-emerald-500 mr-2 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>1 Outlet Cabang</li>
                        <li class="flex items-center"><svg class="h-4 w-4 text-emerald-500 mr-2 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>POS Kasir Utama</li>
                        <li class="flex items-center"><svg class="h-4 w-4 text-emerald-500 mr-2 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>Riwayat Transaksi</li>
                        <li class="flex items-center text-[#8896A6]"><svg class="h-4 w-4 text-[#E2E7EF] mr-2 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>Timbangan IoT</li>
                        <li class="flex items-center text-[#8896A6]"><svg class="h-4 w-4 text-[#E2E7EF] mr-2 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>AI Classifier</li>
                    </ul>
                </div>
                <a href="{{ route('register') }}" class="w-full py-2.5 bg-[#F8F9FC] hover:bg-[#E2E7EF] text-center text-[#1A1D23] rounded-xl text-xs font-semibold block transition-colors border border-[#E2E7EF]">Mulai Trial 3 Hari</a>
            </div>

            <!-- Plan 2 (Popular) -->
            <div class="bg-white border-2 border-[#D4A853]/40 p-8 rounded-2xl space-y-6 flex flex-col justify-between relative shadow-xl shadow-[#D4A853]/10">
                <span class="absolute -top-3 right-6 bg-gradient-to-r from-[#D4A853] to-[#E8C97A] text-white px-3 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider shadow-lg">Populer</span>
                <div class="space-y-4">
                    <div>
                        <h4 class="text-md font-bold text-[#1A1D23]">Pro</h4>
                        <p class="text-[11px] text-[#8896A6]">Untuk bisnis laundry yang berkembang</p>
                    </div>
                    <p class="text-3xl font-extrabold text-[#1A1D23]">Rp 350.000 <span class="text-xs text-[#8896A6] font-medium">/bln</span></p>
                    <ul class="space-y-2 text-xs text-[#4A5568]">
                        <li class="flex items-center"><svg class="h-4 w-4 text-emerald-500 mr-2 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>Up to 5 Outlet</li>
                        <li class="flex items-center"><svg class="h-4 w-4 text-emerald-500 mr-2 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>POS Kasir & Multi-Staf</li>
                        <li class="flex items-center"><svg class="h-4 w-4 text-emerald-500 mr-2 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>Timbangan IoT</li>
                        <li class="flex items-center"><svg class="h-4 w-4 text-emerald-500 mr-2 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>Notifikasi WA Bot</li>
                        <li class="flex items-center text-[#8896A6]"><svg class="h-4 w-4 text-[#E2E7EF] mr-2 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>AI Classifier</li>
                    </ul>
                </div>
                <a href="{{ route('register') }}" class="w-full py-2.5 bg-gradient-to-r from-[#1E3A5F] to-[#2A5082] text-center text-white rounded-xl text-xs font-bold block transition-all shadow-lg shadow-[#1E3A5F]/10 hover:shadow-xl">Mulai Trial 3 Hari</a>
            </div>

            <!-- Plan 3 -->
            <div class="bg-white border border-[#E2E7EF] p-8 rounded-2xl space-y-6 flex flex-col justify-between hover:shadow-xl hover:shadow-[#1E3A5F]/5 transition-all">
                <div class="space-y-4">
                    <div>
                        <h4 class="text-md font-bold text-[#1A1D23]">Enterprise</h4>
                        <p class="text-[11px] text-[#8896A6]">Paket terlengkap tanpa batas</p>
                    </div>
                    <p class="text-3xl font-extrabold text-[#1A1D23]">Rp 750.000 <span class="text-xs text-[#8896A6] font-medium">/bln</span></p>
                    <ul class="space-y-2 text-xs text-[#4A5568]">
                        <li class="flex items-center"><svg class="h-4 w-4 text-emerald-500 mr-2 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>Unlimited Outlet</li>
                        <li class="flex items-center"><svg class="h-4 w-4 text-emerald-500 mr-2 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>Semua Fitur Pro</li>
                        <li class="flex items-center"><svg class="h-4 w-4 text-emerald-500 mr-2 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>AI Classifier</li>
                        <li class="flex items-center"><svg class="h-4 w-4 text-emerald-500 mr-2 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>Laporan Eksekutif</li>
                        <li class="flex items-center"><svg class="h-4 w-4 text-emerald-500 mr-2 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>Priority Support</li>
                    </ul>
                </div>
                <a href="{{ route('register') }}" class="w-full py-2.5 bg-[#F8F9FC] hover:bg-[#E2E7EF] text-center text-[#1A1D23] rounded-xl text-xs font-semibold block transition-colors border border-[#E2E7EF]">Mulai Trial 3 Hari</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="border-t border-[#E2E7EF] bg-[#F8F9FC] py-10">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
            <div class="flex items-center space-x-2">
                <div class="h-6 w-6 rounded bg-gradient-to-br from-[#1E3A5F] to-[#2A5082] flex items-center justify-center">
                    <svg class="h-3 w-3 text-[#D4A853]" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 14a6 6 0 110-12 6 6 0 010 12z"/></svg>
                </div>
                <span class="text-sm font-bold text-[#1E3A5F]">KLIIN</span>
            </div>
            <p class="text-xs text-[#8896A6]">&copy; 2026 KLIIN. All rights reserved.</p>
        </div>
    </footer>

    <!-- Tracking Script (FIXED: dynamic host) -->
    <script>
        function lacakCucian() {
            const subdomain = document.getElementById('trackSubdomain').value.trim();
            const invoice = document.getElementById('trackInvoice').value.trim();
            if (!subdomain || !invoice) {
                alert('Silakan masukkan subdomain outlet dan nomor invoice Anda.');
                return;
            }
            let host = window.location.hostname;
            if (host === '127.0.0.1') host = 'localhost';
            const port = window.location.port ? ':' + window.location.port : '';
            const trackUrl = 'http://' + subdomain + '.' + host + port + '/track/' + invoice;
            window.open(trackUrl, '_blank');
        }
    </script>
</body>
</html>
