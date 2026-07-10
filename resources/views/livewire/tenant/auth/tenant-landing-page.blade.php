@if($dynamicPage)
    <!-- Render Dynamic Modular Landing Page -->
    <div style="
        --color-primary: {{ $theme->primary_color ?? '#1E3A5F' }};
        --color-secondary: {{ $theme->secondary_color ?? '#2A5082' }};
        --color-accent: {{ $theme->accent_color ?? '#D4A853' }};
        --color-background: {{ $theme->background_color ?? '#F8F9FC' }};
        --color-surface: {{ $theme->surface_color ?? '#FFFFFF' }};
        --color-text: {{ $theme->text_color ?? '#4A5568' }};
        --color-heading: {{ $theme->heading_color ?? '#1A1D23' }};
        --border-radius: {{ $theme->border_radius ?? '12px' }};
        font-family: '{{ $theme->body_font ?? 'Outfit' }}', sans-serif;
    " class="min-h-screen bg-[var(--color-background)] text-[var(--color-text)] flex flex-col justify-between">
        
        <!-- Navbar Header -->
        @include('livewire.tenant.website.renderer.header')

        <!-- Page Sections Content -->
        <main class="flex-1">
            @foreach($sections as $section)
                @include('livewire.tenant.website.renderer.section', ['section' => $section])
            @endforeach
        </main>

        <!-- Popup Promos & Campaigns Overlay -->
        @include('livewire.tenant.website.renderer.popups')

        <!-- Footer -->
        @include('livewire.tenant.website.renderer.footer')
    </div>
@else
    <!-- Fallback Welcome Page Default Layout -->
    <div class="min-h-screen bg-[#F8F9FC] text-[#1A1D23] flex flex-col justify-between">
        <!-- Navbar -->
        <nav class="border-b border-[#E2E7EF] bg-white/80 backdrop-blur-xl sticky top-0 z-50">
            <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <div class="h-9 w-9 rounded-xl bg-gradient-to-br from-[#1E3A5F] to-[#2A5082] flex items-center justify-center shadow-md shadow-[#1E3A5F]/15">
                        <svg class="h-4.5 w-4.5 text-[#D4A853]" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 14a6 6 0 110-12 6 6 0 010 12z"/><path d="M10 5a1 1 0 011 1v3.586l2.707 2.707a1 1 0 01-1.414 1.414l-3-3A1 1 0 019 10V6a1 1 0 011-1z"/></svg>
                    </div>
                    <span class="text-lg font-bold text-[#1E3A5F] tracking-wider">{{ $laundryName }}</span>
                </div>
                <div>
                    @auth('tenant')
                        <a href="{{ route('tenant.dashboard') }}" class="px-5 py-2 bg-[#1E3A5F] hover:bg-[#2A5082] text-white rounded-xl text-xs font-bold shadow-lg shadow-[#1E3A5F]/10 transition-all">
                            Dashboard Kasir
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-5 py-2 border border-[#E2E7EF] hover:bg-[#F8F9FC] text-[#1E3A5F] rounded-xl text-xs font-bold transition-all">
                            Masuk Staf
                        </a>
                    @endauth
                </div>
            </div>
        </nav>

        <!-- Content -->
        <main class="flex-1 max-w-5xl mx-auto px-6 py-12 w-full space-y-16">
            <!-- Hero & Tracking Section -->
            <section class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center pt-4">
                <div class="lg:col-span-7 space-y-6">
                    <span class="inline-flex items-center px-4 py-1.5 bg-[#1E3A5F]/5 text-[#1E3A5F] border border-[#1E3A5F]/10 rounded-full text-[10px] font-bold tracking-wider uppercase">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 mr-2 animate-pulse"></span>
                        Outlet Aktif
                    </span>
                    <h1 class="text-4xl md:text-5xl font-extrabold text-[#1A1D23] tracking-tight leading-tight">
                        Selamat Datang di <br><span class="bg-clip-text text-transparent bg-gradient-to-r from-[#1E3A5F] via-[#2A5082] to-[#D4A853]">{{ $laundryName }}</span>
                    </h1>
                    <p class="text-sm text-[#4A5568] leading-relaxed max-w-xl">
                        Kami hadir memberikan layanan cuci dan setrika pakaian premium dengan jaminan kebersihan, kerapian, dan kecepatan pemrosesan menggunakan sistem manajemen modern.
                    </p>
                    
                    <!-- Contact info cards -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        @if($laundryAddress)
                        <div class="flex items-start space-x-2.5 bg-white border border-[#E2E7EF] p-3 rounded-xl">
                            <svg class="h-4 w-4 text-[#D4A853] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span class="text-[#4A5568]">{{ $laundryAddress }}</span>
                        </div>
                        @endif
                        
                        @if($laundryPhone)
                        <a href="https://wa.me/{{ $laundryPhone }}" target="_blank" class="flex items-center space-x-2.5 bg-white border border-[#E2E7EF] p-3 rounded-xl hover:border-emerald-300 transition-colors">
                            <svg class="h-4 w-4 text-emerald-500 shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397 0 11.977 0c3.187.001 6.185 1.24 8.437 3.496 2.25 2.257 3.488 5.259 3.485 8.448-.006 6.625-5.343 11.973-11.928 11.973-2.01-.001-3.987-.508-5.746-1.472L0 24zm6.59-4.846c1.6.95 3.197 1.453 4.803 1.456 5.46 0 9.897-4.417 9.9-9.85.002-2.63-1.018-5.101-2.873-6.958C16.564 3.945 14.103 2.923 11.98 2.923c-5.467 0-9.907 4.417-9.91 9.851-.002 1.83.5 3.606 1.453 5.187l-.953 3.48 3.58-.938z"/></svg>
                            <span class="font-semibold text-emerald-600">WhatsApp: {{ $laundryPhone }}</span>
                        </a>
                        @endif
                    </div>
                </div>

                <!-- Tracking Card -->
                <div class="lg:col-span-5">
                    <div class="bg-white border border-[#E2E7EF] p-6 rounded-2xl shadow-xl shadow-[#1E3A5F]/5 space-y-6">
                        <div class="border-b border-[#E2E7EF] pb-3">
                            <h3 class="text-lg font-bold text-[#1A1D23] flex items-center space-x-2">
                                <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                <span>Lacak Status Laundry</span>
                            </h3>
                            <p class="text-xs text-[#8896A6] mt-1">Pantau proses pencucian pakaian Anda secara real-time</p>
                        </div>

                        <form wire:submit.prevent="track" class="space-y-4">
                            <div>
                                <label for="invoice" class="block text-xs font-semibold text-[#4A5568] uppercase tracking-wider">Nomor Invoice</label>
                                <input wire:model="invoiceNumber" id="invoice" type="text" placeholder="Masukkan nomor invoice (cth: INV-...)" 
                                    class="w-full mt-1.5 px-4 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] placeholder-[#8896A6] rounded-xl text-sm focus:outline-none focus:border-[#1E3A5F] focus:ring-2 focus:ring-[#1E3A5F]/10 transition-all">
                                @error('invoiceNumber') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <button type="submit" class="w-full py-2.5 bg-gradient-to-r from-[#1E3A5F] to-[#2A5082] hover:from-[#2A5082] hover:to-[#1E3A5F] text-white rounded-xl text-sm font-bold shadow-lg shadow-[#1E3A5F]/10 transition-all cursor-pointer">
                                Periksa Status Pengerjaan
                            </button>
                        </form>
                    </div>
                </div>
            </section>

            <!-- Service & Pricing Section -->
            <section class="space-y-8">
                <div class="border-b border-[#E2E7EF] pb-4">
                    <h2 class="text-2xl font-bold text-[#1A1D23]">Daftar Layanan & Harga</h2>
                    <p class="text-sm text-[#8896A6] mt-1">Tarif transparan untuk cucian kiloan maupun satuan</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    @forelse($categories as $cat)
                        @if($cat->services->count() > 0)
                            <div class="bg-white border border-[#E2E7EF] p-6 rounded-2xl space-y-4">
                                <h3 class="text-md font-bold text-[#1E3A5F] border-b border-[#E2E7EF] pb-2 flex items-center">
                                    <span class="h-1.5 w-1.5 rounded-full bg-[#D4A853] mr-2"></span>
                                    {{ $cat->name }}
                                </h3>
                                <div class="divide-y divide-[#E2E7EF]">
                                    @foreach($cat->services as $srv)
                                        <div class="py-3 flex justify-between items-center text-sm">
                                            <div>
                                                <p class="font-semibold text-[#1A1D23]">{{ $srv->name }}</p>
                                                <p class="text-xs text-[#8896A6] mt-0.5">{{ $srv->description }}</p>
                                            </div>
                                            <div class="text-right">
                                                @php
                                                    $priceObj = $srv->prices->where('price_type', 'regular')->first();
                                                    $price = $priceObj ? $priceObj->price : 0;
                                                @endphp
                                                <span class="font-bold text-[#1A1D23]">Rp {{ number_format($price, 0, ',', '.') }}</span>
                                                <span class="text-[10px] text-[#8896A6] block">/ {{ $srv->unit }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @empty
                        <div class="col-span-2 bg-white border border-[#E2E7EF] p-8 text-center rounded-2xl">
                            <p class="text-sm text-[#8896A6]">Belum ada daftar layanan yang dikonfigurasi oleh admin outlet.</p>
                        </div>
                    @endforelse
                </div>
            </section>
        </main>

        <!-- Footer -->
        <footer class="border-t border-[#E2E7EF] bg-white py-8 mt-12">
            <div class="max-w-6xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                <div class="flex items-center space-x-2">
                    <div class="h-6 w-6 rounded bg-gradient-to-br from-[#1E3A5F] to-[#2A5082] flex items-center justify-center">
                        <svg class="h-3 w-3 text-[#D4A853]" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 14a6 6 0 110-12 6 6 0 010 12z"/></svg>
                    </div>
                    <span class="text-xs font-bold text-[#1E3A5F]">{{ $laundryName }}</span>
                </div>
                <p class="text-xs text-[#8896A6]">&copy; 2026 {{ $laundryName }}. Powered by KLIIN.</p>
            </div>
        </footer>
    </div>
@endif
