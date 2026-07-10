<div class="space-y-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-4 md:space-y-0">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-[#1A1D23]">Dashboard Website</h1>
            <p class="text-sm text-[#8896A6] mt-1">Kelola publikasi landing page kustom usaha laundry Anda</p>
        </div>
        
        <div class="flex items-center space-x-2">
            <a href="{{ $siteUrl }}" target="_blank" class="px-5 py-2.5 bg-white border border-[#E2E7EF] text-[#4A5568] hover:bg-[#F8F9FC] rounded-xl text-xs font-bold transition-all shadow-sm flex items-center space-x-2">
                <svg class="h-4 w-4 text-[#8896A6]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                <span>Kunjungi Website</span>
            </a>
            
            <button wire:click="createHomepage" class="px-5 py-2.5 bg-gradient-to-r from-[#1E3A5F] to-[#2A5082] hover:from-[#2A5082] hover:to-[#1E3A5F] text-white rounded-xl text-xs font-bold shadow-lg shadow-[#1E3A5F]/10 transition-all cursor-pointer flex items-center space-x-2">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                <span>{{ $homepage ? 'Edit Halaman Utama' : 'Aktifkan Website Kustom' }}</span>
            </button>
        </div>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white border border-[#E2E7EF] rounded-2xl p-6 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs text-[#8896A6] block uppercase tracking-wider font-semibold">Status Website</span>
                <span class="text-lg font-bold text-[#1A1D23] block mt-1">
                    @if($homepage)
                        <span class="text-emerald-500 font-semibold flex items-center space-x-1.5 text-sm">
                            <span class="h-2 w-2 bg-emerald-500 rounded-full animate-pulse"></span>
                            <span>{{ ucfirst($homepage->status) }}</span>
                        </span>
                    @else
                        <span class="text-amber-500 text-sm">Welcome Page Default</span>
                    @endif
                </span>
            </div>
            <div class="p-3 bg-slate-50 text-slate-400 rounded-2xl">
                🌐
            </div>
        </div>

        <div class="bg-white border border-[#E2E7EF] rounded-2xl p-6 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs text-[#8896A6] block uppercase tracking-wider font-semibold">Jumlah Halaman</span>
                <span class="text-2xl font-bold text-[#1A1D23] block mt-1">{{ $totalPagesCount }}</span>
            </div>
            <div class="p-3 bg-slate-50 text-slate-400 rounded-2xl">
                📄
            </div>
        </div>

        <div class="bg-white border border-[#E2E7EF] rounded-2xl p-6 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs text-[#8896A6] block uppercase tracking-wider font-semibold">Pustaka Media</span>
                <span class="text-2xl font-bold text-[#1A1D23] block mt-1">{{ $totalMediaCount }} berkas</span>
            </div>
            <div class="p-3 bg-slate-50 text-slate-400 rounded-2xl">
                🖼️
            </div>
        </div>

        <div class="bg-white border border-[#E2E7EF] rounded-2xl p-6 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs text-[#8896A6] block uppercase tracking-wider font-semibold">Popup Iklan Aktif</span>
                <span class="text-2xl font-bold text-[#1A1D23] block mt-1">{{ $activePopupsCount }}</span>
            </div>
            <div class="p-3 bg-slate-50 text-slate-400 rounded-2xl">
                📢
            </div>
        </div>
    </div>

    <!-- Management Modules Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Landing Pages -->
        <a href="{{ route('tenant.website.pages') }}" class="bg-white border border-[#E2E7EF] hover:border-[#1E3A5F] rounded-2xl p-6 shadow-sm hover:shadow-md transition-all flex flex-col justify-between group h-48">
            <div class="space-y-2">
                <span class="text-2xl block">📄</span>
                <h3 class="text-md font-bold text-[#1A1D23] group-hover:text-[#1E3A5F]">Halaman Website</h3>
                <p class="text-xs text-[#8896A6] leading-relaxed">Kelola landing page, beranda utama, atau halaman promo produk secara dinamis.</p>
            </div>
            <span class="text-xs font-bold text-[#1E3A5F] flex items-center space-x-1.5 self-end">
                <span>Kelola Halaman</span>
                <span>→</span>
            </span>
        </a>

        <!-- Media Library -->
        <a href="{{ route('tenant.website.media') }}" class="bg-white border border-[#E2E7EF] hover:border-[#1E3A5F] rounded-2xl p-6 shadow-sm hover:shadow-md transition-all flex flex-col justify-between group h-48">
            <div class="space-y-2">
                <span class="text-2xl block">🖼️</span>
                <h3 class="text-md font-bold text-[#1A1D23] group-hover:text-[#1E3A5F]">Pustaka Media</h3>
                <p class="text-xs text-[#8896A6] leading-relaxed">Unggah file banner, foto outlet laundry, atau video testimonial secara terpusat.</p>
            </div>
            <span class="text-xs font-bold text-[#1E3A5F] flex items-center space-x-1.5 self-end">
                <span>Buka Media</span>
                <span>→</span>
            </span>
        </a>

        <!-- Popup Campaigns -->
        <a href="{{ route('tenant.website.popups') }}" class="bg-white border border-[#E2E7EF] hover:border-[#1E3A5F] rounded-2xl p-6 shadow-sm hover:shadow-md transition-all flex flex-col justify-between group h-48">
            <div class="space-y-2">
                <span class="text-2xl block">📢</span>
                <h3 class="text-md font-bold text-[#1A1D23] group-hover:text-[#1E3A5F]">Popup & Promosi</h3>
                <p class="text-xs text-[#8896A6] leading-relaxed">Atur modal newsletter, banner promo diskon pop-up, atau floating whatsapp chat.</p>
            </div>
            <span class="text-xs font-bold text-[#1E3A5F] flex items-center space-x-1.5 self-end">
                <span>Atur Promosi</span>
                <span>→</span>
            </span>
        </a>

        <!-- Navigation Builder -->
        <a href="{{ route('tenant.website.navigation') }}" class="bg-white border border-[#E2E7EF] hover:border-[#1E3A5F] rounded-2xl p-6 shadow-sm hover:shadow-md transition-all flex flex-col justify-between group h-48">
            <div class="space-y-2">
                <span class="text-2xl block">🔗</span>
                <h3 class="text-md font-bold text-[#1A1D23] group-hover:text-[#1E3A5F]">Menu Navigasi</h3>
                <p class="text-xs text-[#8896A6] leading-relaxed">Susun menu link navigasi di bagian atas (header) atau bawah (footer) website Anda.</p>
            </div>
            <span class="text-xs font-bold text-[#1E3A5F] flex items-center space-x-1.5 self-end">
                <span>Susun Menu</span>
                <span>→</span>
            </span>
        </a>

        <!-- Theme Settings -->
        <a href="{{ route('tenant.website.theme') }}" class="bg-white border border-[#E2E7EF] hover:border-[#1E3A5F] rounded-2xl p-6 shadow-sm hover:shadow-md transition-all flex flex-col justify-between group h-48">
            <div class="space-y-2">
                <span class="text-2xl block">🎨</span>
                <h3 class="text-md font-bold text-[#1A1D23] group-hover:text-[#1E3A5F]">Pengaturan Tema</h3>
                <p class="text-xs text-[#8896A6] leading-relaxed">Ubah palet warna utama brand, model lengkungan tombol, dan font teks global.</p>
            </div>
            <span class="text-xs font-bold text-[#1E3A5F] flex items-center space-x-1.5 self-end">
                <span>Atur Tema</span>
                <span>→</span>
            </span>
        </a>
    </div>
</div>
