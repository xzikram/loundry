<!DOCTYPE html>
<html lang="id" class="h-full scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spinly — Smart Laundry Management Platform</title>
    <meta name="description" content="Platform manajemen laundry cerdas dengan POS, IoT, AI, dan WhatsApp Bot otomatis untuk UMKM laundry Indonesia.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Outfit', sans-serif; }
        @keyframes float { 0%,100% { transform: translateY(0px); } 50% { transform: translateY(-12px); } }
        @keyframes glow { 0%,100% { opacity: 0.4; } 50% { opacity: 0.8; } }
        @keyframes slideUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        .animate-float { animation: float 6s ease-in-out infinite; }
        .animate-glow { animation: glow 3s ease-in-out infinite; }
        .animate-slide-up { animation: slideUp 0.8s ease-out forwards; }
        .animation-delay-200 { animation-delay: 0.2s; }
        .animation-delay-400 { animation-delay: 0.4s; }
        .animation-delay-600 { animation-delay: 0.6s; }
        .glass { background: rgba(255,255,255,0.08); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.12); }
    </style>
</head>
<body class="h-full antialiased bg-white text-[#1A1D23]">
    
    <!-- Navbar -->
    <nav class="border-b border-white/10 bg-[#0F1A2E]/90 backdrop-blur-xl fixed top-0 left-0 right-0 z-50">
        <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
            <div class="flex items-center space-x-2.5">
                <div class="h-8 w-8 rounded-lg bg-gradient-to-br from-[#D4A853] via-[#E8C97A] to-[#10B981] flex items-center justify-center shadow-lg shadow-[#D4A853]/25 shrink-0">
                    <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" d="M12 3a9 9 0 1 0 9 9c0-2-1.5-3.5-3-3s-3 1.5-3 3a3 3 0 1 1-6 0c0-1.5 1.2-3 3-3" />
                        <path d="M12 10.5l.3.7.7.3-.7.3-.3.7-.3-.7-.7-.3.7-.3z" fill="currentColor"/>
                    </svg>
                </div>
                <span class="text-lg font-black tracking-wider bg-clip-text text-transparent bg-gradient-to-r from-[#D4A853] to-[#E8C97A]">Spinly</span>
            </div>
            <div class="flex items-center space-x-6">
                <a href="#features" class="text-sm text-slate-300 hover:text-[#D4A853] transition-colors hidden sm:block font-medium">Fitur</a>
                <a href="#pricing" class="text-sm text-slate-300 hover:text-[#D4A853] transition-colors hidden sm:block font-medium">Harga</a>
                <a href="{{ route('login') }}" class="text-sm font-bold text-white hover:text-[#D4A853] transition-colors">
                    Masuk
                </a>
                <a href="{{ route('register') }}" class="px-5 py-2.5 bg-gradient-to-r from-[#D4A853] to-[#10B981] hover:from-[#E8C97A] hover:to-[#059669] text-white rounded-xl text-xs font-bold shadow-lg shadow-[#D4A853]/20 transition-all hover:-translate-y-0.5">
                    Daftar Gratis
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="relative overflow-hidden bg-gradient-to-br from-[#0F1A2E] via-[#162240] to-[#1A2D4A] pt-24 pb-0 min-h-[90vh] flex items-center">
        <!-- Decorative Background Elements -->
        <div class="absolute top-20 left-10 w-72 h-72 bg-[#D4A853]/10 rounded-full blur-3xl animate-glow"></div>
        <div class="absolute bottom-20 right-10 w-96 h-96 bg-[#10B981]/8 rounded-full blur-3xl animate-glow animation-delay-200"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-gradient-radial from-[#D4A853]/5 to-transparent rounded-full blur-3xl"></div>
        
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-2 gap-12 items-end relative z-10 w-full">
            <!-- Left: Text Content -->
            <div class="space-y-8 pb-20 lg:pb-32 animate-slide-up">
                <span class="inline-flex items-center px-4 py-2 bg-white/5 border border-white/10 rounded-full text-xs font-bold tracking-wider uppercase text-[#D4A853] backdrop-blur-sm">
                    <span class="h-2 w-2 rounded-full bg-[#10B981] mr-2.5 animate-pulse shadow-sm shadow-[#10B981]/50"></span>
                    Platform Laundry Cerdas #1 Indonesia
                </span>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-white tracking-tight leading-[1.1]">
                    Kelola Bisnis Laundry dengan
                    <span class="bg-clip-text text-transparent bg-gradient-to-r from-[#D4A853] via-[#E8C97A] to-[#10B981]">Teknologi Terdepan</span>
                </h1>
                <p class="text-base md:text-lg text-slate-300 leading-relaxed max-w-lg font-medium">
                    Multi-tenant cloud platform dengan POS Kasir, Timbangan IoT, Klasifikasi AI, QR-Rack Locator, WhatsApp Bot Otomatis, dan Laporan Keuangan lengkap.
                </p>

                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-[#D4A853] to-[#10B981] hover:from-[#E8C97A] hover:to-[#059669] text-white rounded-xl text-sm font-bold shadow-2xl shadow-[#D4A853]/20 transition-all hover:-translate-y-0.5 group">
                        Mulai Gratis Sekarang
                        <svg class="ml-2 h-4 w-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                    <a href="#features" class="inline-flex items-center justify-center px-8 py-4 border-2 border-white/15 text-white hover:bg-white/5 rounded-xl text-sm font-bold transition-all backdrop-blur-sm">
                        Lihat Fitur →
                    </a>
                </div>

                <!-- Stats Row -->
                <div class="flex flex-wrap gap-8 pt-4">
                    <div>
                        <p class="text-2xl font-black text-[#D4A853]">500+</p>
                        <p class="text-[11px] text-slate-400 font-semibold uppercase tracking-wider">Outlet Aktif</p>
                    </div>
                    <div>
                        <p class="text-2xl font-black text-[#10B981]">50K+</p>
                        <p class="text-[11px] text-slate-400 font-semibold uppercase tracking-wider">Transaksi/Bulan</p>
                    </div>
                    <div>
                        <p class="text-2xl font-black text-white">4.9★</p>
                        <p class="text-[11px] text-slate-400 font-semibold uppercase tracking-wider">Rating User</p>
                    </div>
                </div>
            </div>

            <!-- Right: Brand Ambassador Image -->
            <div class="hidden lg:flex justify-end items-end animate-slide-up animation-delay-400">
                <div class="relative">
                    <!-- Glow behind image -->
                    <div class="absolute -inset-8 bg-gradient-to-t from-[#D4A853]/20 via-[#10B981]/10 to-transparent rounded-full blur-3xl"></div>
                    <img src="/images/brand/hero.png" alt="Spinly Brand Ambassador" class="relative z-10 h-[520px] w-auto object-contain drop-shadow-2xl">
                    
                    <!-- Floating glass card -->
                    <div class="absolute top-12 -left-16 glass rounded-2xl px-5 py-4 animate-float z-20 shadow-xl">
                        <div class="flex items-center space-x-3">
                            <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-[#10B981] to-[#059669] flex items-center justify-center">
                                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-white">Order Selesai!</p>
                                <p class="text-[10px] text-slate-300">INV-260710-001</p>
                            </div>
                        </div>
                    </div>

                    <!-- Floating stat card -->
                    <div class="absolute bottom-32 -left-20 glass rounded-2xl px-5 py-4 animate-float animation-delay-200 z-20 shadow-xl">
                        <p class="text-[10px] text-slate-300 font-semibold uppercase tracking-wider">Omzet Hari Ini</p>
                        <p class="text-xl font-black text-[#D4A853] mt-1">Rp 2.8 Jt</p>
                        <p class="text-[10px] text-[#10B981] font-bold mt-0.5">↑ 24% vs kemarin</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom gradient fade -->
        <div class="absolute bottom-0 left-0 right-0 h-24 bg-gradient-to-t from-white to-transparent"></div>
    </header>

    <!-- Tracking Widget Section -->
    <section class="relative -mt-12 z-20 max-w-3xl mx-auto px-6">
        <div class="bg-white border border-[#E2E7EF] p-8 rounded-2xl shadow-2xl shadow-[#1E3A5F]/10 relative overflow-hidden">
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-[#D4A853] via-[#10B981] to-[#3B82F6]"></div>
            <div class="flex flex-col md:flex-row md:items-end gap-6">
                <div class="flex-1 space-y-1">
                    <h3 class="text-lg font-black text-[#1A1D23] flex items-center space-x-2">
                        <span class="h-2.5 w-2.5 rounded-full bg-[#10B981] animate-pulse"></span>
                        <span>Lacak Cucian Anda</span>
                    </h3>
                    <p class="text-xs text-[#8896A6] font-medium">Masukkan subdomain outlet dan nomor invoice untuk cek status real-time.</p>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-5 gap-3 mt-5">
                <input id="trackSubdomain" type="text" placeholder="Subdomain (cth: demo)"
                    class="sm:col-span-2 px-4 py-3 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] placeholder-[#8896A6] rounded-xl text-xs font-medium focus:outline-none focus:border-[#D4A853] focus:ring-2 focus:ring-[#D4A853]/10 transition-all">
                <input id="trackInvoice" type="text" placeholder="No Invoice (cth: INV-260630...)"
                    class="sm:col-span-2 px-4 py-3 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] placeholder-[#8896A6] rounded-xl text-xs font-medium focus:outline-none focus:border-[#D4A853] focus:ring-2 focus:ring-[#D4A853]/10 transition-all">
                <button onclick="lacakCucian()" 
                    class="sm:col-span-1 py-3 bg-gradient-to-r from-[#D4A853] to-[#10B981] hover:from-[#E8C97A] hover:to-[#059669] text-white rounded-xl text-xs font-bold transition-all shadow-lg shadow-[#D4A853]/15 cursor-pointer hover:-translate-y-0.5">
                    🔍 Lacak
                </button>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-24 bg-white relative overflow-hidden">
        <div class="absolute top-0 left-0 w-96 h-96 bg-[#D4A853]/3 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-[#10B981]/3 rounded-full blur-3xl"></div>
        
        <div class="max-w-7xl mx-auto px-6 space-y-16 relative z-10">
            <div class="text-center space-y-4">
                <span class="inline-flex items-center px-3 py-1 bg-[#D4A853]/10 text-[#D4A853] rounded-full text-[10px] font-bold uppercase tracking-wider">Fitur Premium</span>
                <h2 class="text-3xl md:text-4xl font-black text-[#1A1D23] tracking-tight">Fitur Unggulan yang <span class="bg-clip-text text-transparent bg-gradient-to-r from-[#D4A853] to-[#10B981]">Mengubah Bisnis</span></h2>
                <p class="text-sm text-[#4A5568] max-w-lg mx-auto font-medium">Teknologi tinggi yang memecahkan masalah operasional terbesar toko laundry Anda.</p>
            </div>

            <!-- Features with Ambassador Image -->
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 items-center">
                <!-- Left Features (2 cols) -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-gradient-to-br from-[#0F1A2E] to-[#1A2D4A] p-6 rounded-2xl space-y-3 group hover:-translate-y-0.5 transition-all duration-300 shadow-lg shadow-[#0F1A2E]/10">
                        <div class="p-3 bg-[#D4A853]/15 text-[#D4A853] rounded-xl w-fit">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
                        </div>
                        <h3 class="text-base font-bold text-white">Timbangan IoT Bluetooth</h3>
                        <p class="text-xs text-slate-300 leading-relaxed">POS membaca berat cucian langsung dari timbangan digital via Bluetooth. Anti-kecurangan kasir.</p>
                    </div>

                    <div class="bg-gradient-to-br from-[#0F1A2E] to-[#1A2D4A] p-6 rounded-2xl space-y-3 group hover:-translate-y-0.5 transition-all duration-300 shadow-lg shadow-[#0F1A2E]/10">
                        <div class="p-3 bg-[#10B981]/15 text-[#10B981] rounded-xl w-fit">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <h3 class="text-base font-bold text-white">AI Clothes Classifier</h3>
                        <p class="text-xs text-slate-300 leading-relaxed">Foto tumpukan baju otomatis dipindai AI untuk mendeteksi jumlah dan jenis pakaian secara instan.</p>
                    </div>
                </div>

                <!-- Center: Ambassador Image (1 col) -->
                <div class="lg:col-span-1 hidden lg:flex justify-center">
                    <div class="relative">
                        <div class="absolute -inset-4 bg-gradient-to-b from-[#D4A853]/15 to-[#10B981]/15 rounded-full blur-2xl"></div>
                        <img src="/images/brand/features.png" alt="Spinly Features" class="relative z-10 h-[380px] w-auto object-contain rounded-2xl">
                    </div>
                </div>

                <!-- Right Features (2 cols) -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-gradient-to-br from-[#0F1A2E] to-[#1A2D4A] p-6 rounded-2xl space-y-3 group hover:-translate-y-0.5 transition-all duration-300 shadow-lg shadow-[#0F1A2E]/10">
                        <div class="p-3 bg-[#3B82F6]/15 text-[#3B82F6] rounded-xl w-fit">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                        </div>
                        <h3 class="text-base font-bold text-white">QR-Rack Locator</h3>
                        <p class="text-xs text-slate-300 leading-relaxed">Lacak posisi pakaian di rak penyimpanan melalui scan QR code. Hemat waktu staf.</p>
                    </div>

                    <div class="bg-gradient-to-br from-[#0F1A2E] to-[#1A2D4A] p-6 rounded-2xl space-y-3 group hover:-translate-y-0.5 transition-all duration-300 shadow-lg shadow-[#0F1A2E]/10">
                        <div class="p-3 bg-[#F59E0B]/15 text-[#F59E0B] rounded-xl w-fit">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        </div>
                        <h3 class="text-base font-bold text-white">WhatsApp Notification</h3>
                        <p class="text-xs text-slate-300 leading-relaxed">Notifikasi status cucian otomatis dikirim ke WhatsApp pelanggan dengan tombol tracking.</p>
                    </div>
                </div>
            </div>

            <!-- Additional Features Row -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-[#F8F9FC] border border-[#E2E7EF] p-6 rounded-2xl space-y-3 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                    <div class="flex items-center space-x-3">
                        <div class="p-2.5 bg-gradient-to-br from-[#10B981] to-[#059669] text-white rounded-xl shadow-sm"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg></div>
                        <h4 class="text-sm font-bold text-[#1A1D23]">Laporan Keuangan</h4>
                    </div>
                    <p class="text-xs text-[#4A5568] font-medium">Laba-rugi, rekap harian/bulanan, top services, dan analisis pelanggan.</p>
                </div>
                <div class="bg-[#F8F9FC] border border-[#E2E7EF] p-6 rounded-2xl space-y-3 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                    <div class="flex items-center space-x-3">
                        <div class="p-2.5 bg-gradient-to-br from-[#D4A853] to-[#B8862D] text-white rounded-xl shadow-sm"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg></div>
                        <h4 class="text-sm font-bold text-[#1A1D23]">Manajemen Pelanggan & Loyalty</h4>
                    </div>
                    <p class="text-xs text-[#4A5568] font-medium">CRM pelanggan, membership tier, poin loyalty, dan riwayat transaksi.</p>
                </div>
                <div class="bg-[#F8F9FC] border border-[#E2E7EF] p-6 rounded-2xl space-y-3 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                    <div class="flex items-center space-x-3">
                        <div class="p-2.5 bg-gradient-to-br from-[#3B82F6] to-[#1D4ED8] text-white rounded-xl shadow-sm"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg></div>
                        <h4 class="text-sm font-bold text-[#1A1D23]">Inventaris & Staf</h4>
                    </div>
                    <p class="text-xs text-[#4A5568] font-medium">Stok bahan baku, alert low stock, CRUD staf, absensi, dan shift management.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonial / Trust Section -->
    <section class="py-20 bg-gradient-to-br from-[#0F1A2E] via-[#162240] to-[#1A2D4A] relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-[#D4A853]/8 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-[#10B981]/8 rounded-full blur-3xl"></div>
        <div class="max-w-5xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center relative z-10">
            <div class="flex justify-center">
                <div class="relative">
                    <div class="absolute -inset-6 bg-gradient-to-br from-[#D4A853]/15 to-[#10B981]/15 rounded-3xl blur-2xl"></div>
                    <img src="/images/brand/testimonial.png" alt="Spinly Trusted by Business" class="relative z-10 h-[350px] w-auto object-contain rounded-2xl shadow-2xl">
                </div>
            </div>
            <div class="space-y-8">
                <span class="inline-flex items-center px-3 py-1 bg-[#D4A853]/15 text-[#D4A853] rounded-full text-[10px] font-bold uppercase tracking-wider">Dipercaya Ribuan Outlet</span>
                <h2 class="text-3xl md:text-4xl font-black text-white tracking-tight leading-tight">
                    Bergabung dengan <span class="text-[#D4A853]">500+</span> pemilik laundry yang sudah <span class="text-[#10B981]">mengoptimalkan</span> bisnisnya
                </h2>
                <p class="text-sm text-slate-300 leading-relaxed font-medium">
                    "Spinly mengubah cara kami mengelola operasional. Dari yang sebelumnya kacau dengan pencatatan manual, sekarang semua otomatis dan transparan. Omzet naik 35% dalam 3 bulan pertama."
                </p>
                <div class="flex items-center space-x-4">
                    <div class="h-12 w-12 rounded-full bg-gradient-to-br from-[#D4A853] to-[#10B981] flex items-center justify-center text-white font-black text-lg shadow-lg">A</div>
                    <div>
                        <p class="text-sm font-bold text-white">Amelia Putri</p>
                        <p class="text-xs text-slate-400 font-medium">Owner, Fresh & Clean Laundry — Jakarta</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="py-24 max-w-5xl mx-auto px-6 space-y-12">
        <div class="text-center space-y-4">
            <span class="inline-flex items-center px-3 py-1 bg-[#10B981]/10 text-[#10B981] rounded-full text-[10px] font-bold uppercase tracking-wider">Harga Transparan</span>
            <h2 class="text-3xl md:text-4xl font-black text-[#1A1D23] tracking-tight">Paket Langganan</h2>
            <p class="text-sm text-[#4A5568] font-medium">Pilih paket terbaik untuk ekspansi bisnis laundry Anda.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Plan 1 -->
            <div class="bg-white border border-[#E2E7EF] p-8 rounded-2xl space-y-6 flex flex-col justify-between hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                <div class="space-y-4">
                    <div>
                        <h4 class="text-md font-bold text-[#1A1D23]">Starter</h4>
                        <p class="text-[11px] text-[#8896A6] font-medium">Untuk outlet laundry pemula</p>
                    </div>
                    <p class="text-3xl font-black text-[#1A1D23]">Rp 150.000 <span class="text-xs text-[#8896A6] font-medium">/bln</span></p>
                    <ul class="space-y-2.5 text-xs text-[#4A5568] font-medium">
                        <li class="flex items-center"><svg class="h-4 w-4 text-[#10B981] mr-2 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>1 Outlet Cabang</li>
                        <li class="flex items-center"><svg class="h-4 w-4 text-[#10B981] mr-2 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>POS Kasir Utama</li>
                        <li class="flex items-center"><svg class="h-4 w-4 text-[#10B981] mr-2 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>Riwayat Transaksi</li>
                        <li class="flex items-center text-[#8896A6]"><svg class="h-4 w-4 text-[#E2E7EF] mr-2 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>Timbangan IoT</li>
                        <li class="flex items-center text-[#8896A6]"><svg class="h-4 w-4 text-[#E2E7EF] mr-2 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>AI Classifier</li>
                    </ul>
                </div>
                <a href="{{ route('register') }}" class="w-full py-3 bg-[#F8F9FC] hover:bg-[#E2E7EF] text-center text-[#1A1D23] rounded-xl text-xs font-bold block transition-colors border border-[#E2E7EF]">Mulai Trial 3 Hari</a>
            </div>

            <!-- Plan 2 (Popular) -->
            <div class="bg-gradient-to-br from-[#0F1A2E] to-[#1A2D4A] border-2 border-[#D4A853]/30 p-8 rounded-2xl space-y-6 flex flex-col justify-between relative shadow-2xl shadow-[#D4A853]/10 hover:-translate-y-1 transition-all duration-300">
                <span class="absolute -top-3 right-6 bg-gradient-to-r from-[#D4A853] to-[#10B981] text-white px-4 py-1 rounded-full text-[9px] font-black uppercase tracking-wider shadow-lg">🔥 Populer</span>
                <div class="space-y-4">
                    <div>
                        <h4 class="text-md font-bold text-white">Pro</h4>
                        <p class="text-[11px] text-slate-400 font-medium">Untuk bisnis laundry yang berkembang</p>
                    </div>
                    <p class="text-3xl font-black text-[#D4A853]">Rp 350.000 <span class="text-xs text-slate-400 font-medium">/bln</span></p>
                    <ul class="space-y-2.5 text-xs text-slate-300 font-medium">
                        <li class="flex items-center"><svg class="h-4 w-4 text-[#10B981] mr-2 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>Up to 5 Outlet</li>
                        <li class="flex items-center"><svg class="h-4 w-4 text-[#10B981] mr-2 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>POS Kasir & Multi-Staf</li>
                        <li class="flex items-center"><svg class="h-4 w-4 text-[#10B981] mr-2 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>Timbangan IoT</li>
                        <li class="flex items-center"><svg class="h-4 w-4 text-[#10B981] mr-2 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>Notifikasi WA Bot</li>
                        <li class="flex items-center text-slate-500"><svg class="h-4 w-4 text-slate-600 mr-2 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>AI Classifier</li>
                    </ul>
                </div>
                <a href="{{ route('register') }}" class="w-full py-3 bg-gradient-to-r from-[#D4A853] to-[#10B981] text-center text-white rounded-xl text-xs font-black block transition-all shadow-lg shadow-[#D4A853]/20 hover:shadow-xl">Mulai Trial 3 Hari</a>
            </div>

            <!-- Plan 3 -->
            <div class="bg-white border border-[#E2E7EF] p-8 rounded-2xl space-y-6 flex flex-col justify-between hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                <div class="space-y-4">
                    <div>
                        <h4 class="text-md font-bold text-[#1A1D23]">Enterprise</h4>
                        <p class="text-[11px] text-[#8896A6] font-medium">Paket terlengkap tanpa batas</p>
                    </div>
                    <p class="text-3xl font-black text-[#1A1D23]">Rp 750.000 <span class="text-xs text-[#8896A6] font-medium">/bln</span></p>
                    <ul class="space-y-2.5 text-xs text-[#4A5568] font-medium">
                        <li class="flex items-center"><svg class="h-4 w-4 text-[#10B981] mr-2 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>Unlimited Outlet</li>
                        <li class="flex items-center"><svg class="h-4 w-4 text-[#10B981] mr-2 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>Semua Fitur Pro</li>
                        <li class="flex items-center"><svg class="h-4 w-4 text-[#10B981] mr-2 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>AI Classifier</li>
                        <li class="flex items-center"><svg class="h-4 w-4 text-[#10B981] mr-2 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>Laporan Eksekutif</li>
                        <li class="flex items-center"><svg class="h-4 w-4 text-[#10B981] mr-2 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>Priority Support</li>
                    </ul>
                </div>
                <a href="{{ route('register') }}" class="w-full py-3 bg-[#F8F9FC] hover:bg-[#E2E7EF] text-center text-[#1A1D23] rounded-xl text-xs font-bold block transition-colors border border-[#E2E7EF]">Mulai Trial 3 Hari</a>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-[#D4A853] via-[#C9963E] to-[#10B981] relative overflow-hidden">
        <div class="absolute top-0 right-0 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>
        <div class="max-w-4xl mx-auto px-6 text-center space-y-8 relative z-10">
            <h2 class="text-3xl md:text-4xl font-black text-white tracking-tight">Siap Tingkatkan Bisnis Laundry Anda?</h2>
            <p class="text-sm text-white/80 max-w-lg mx-auto font-medium">Daftar sekarang dan dapatkan trial 3 hari gratis. Tanpa kartu kredit. Tanpa komitmen.</p>
            <a href="{{ route('register') }}" class="inline-flex items-center px-10 py-4 bg-white text-[#0F1A2E] rounded-xl text-sm font-black shadow-2xl shadow-[#0F1A2E]/20 hover:-translate-y-1 transition-all duration-300 group">
                Daftar Gratis Sekarang
                <svg class="ml-2 h-4 w-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="border-t border-[#E2E7EF] bg-[#0F1A2E] py-12">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
            <div class="flex items-center space-x-2.5">
                <div class="h-8 w-8 rounded-lg bg-gradient-to-br from-[#D4A853] via-[#E8C97A] to-[#10B981] flex items-center justify-center shadow-lg shadow-[#D4A853]/25 shrink-0">
                    <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" d="M12 3a9 9 0 1 0 9 9c0-2-1.5-3.5-3-3s-3 1.5-3 3a3 3 0 1 1-6 0c0-1.5 1.2-3 3-3" />
                        <path d="M12 10.5l.3.7.7.3-.7.3-.3.7-.3-.7-.7-.3.7-.3z" fill="currentColor"/>
                    </svg>
                </div>
                <span class="text-sm font-black bg-clip-text text-transparent bg-gradient-to-r from-[#D4A853] to-[#E8C97A]">Spinly</span>
            </div>
            <p class="text-xs text-slate-400 font-medium">&copy; 2026 Spinly. All rights reserved.</p>
        </div>
    </footer>

    <!-- Tracking Script -->
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
