@php
    $bgColor = $section->settings['bg_color'] ?? '#FFFFFF';
    $textColor = $section->settings['text_color'] ?? '#4A5568';
    
    // Spacing mapping
    $paddings = [
        'none' => 'py-0',
        'small' => 'py-10',
        'medium' => 'py-20 lg:py-28',
        'large' => 'py-28 lg:py-36',
    ];
    $paddingTop = $paddings[$section->settings['padding_top'] ?? 'medium'] ?? 'py-20 lg:py-28';
    $paddingBottom = $paddings[$section->settings['padding_bottom'] ?? 'medium'] ?? 'py-20 lg:py-28';
@endphp

<section id="{{ $section->section_type }}" class="px-6 {{ $paddingTop }} {{ $paddingBottom }} transition-all duration-300 relative overflow-hidden" style="background-color: {{ $bgColor }}; color: {{ $textColor }};">
    
    <!-- Background subtle lights for premium look -->
    <div class="absolute top-0 left-1/4 w-96 h-96 bg-[var(--color-primary)]/5 rounded-full blur-3xl -z-10"></div>
    <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-[var(--color-accent)]/5 rounded-full blur-3xl -z-10"></div>

    <div class="max-w-6xl mx-auto">
        
        <!-- HERO SECTION -->
        @if($section->section_type === 'hero')
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
                <div class="lg:col-span-7 space-y-6 text-left">
                    @if(isset($section->content['eyebrow']) && $section->content['eyebrow'])
                        <span class="inline-flex items-center px-4 py-1.5 border border-[var(--color-primary)]/15 text-xs font-black uppercase tracking-widest bg-clip-text text-transparent bg-gradient-to-r from-[var(--color-primary)] to-[var(--color-accent)]" style="border-radius: 999px; background-color: color-mix(in srgb, var(--color-primary) 5%, transparent);">
                            <span class="h-1.5 w-1.5 rounded-full mr-2 animate-pulse" style="background-color: var(--color-accent);"></span>
                            {{ $section->content['eyebrow'] }}
                        </span>
                    @endif
                    
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-black leading-[1.1] tracking-tight" style="color: var(--color-heading); font-family: '{{ $theme->heading_font ?? 'Outfit' }}', sans-serif;">
                        {{ $section->content['title'] ?? 'Laundry Kilat Bersih' }}
                    </h1>
                    
                    <p class="text-sm md:text-base leading-relaxed opacity-80 max-w-xl font-medium">
                        {{ $section->content['description'] ?? 'Tuliskan slogan utama outlet laundry Anda di sini.' }}
                    </p>
                    
                    <div class="pt-3 flex flex-wrap items-center gap-4">
                        @if(isset($section->content['button_text']) && $section->content['button_text'])
                            <a href="{{ $section->content['button_url'] ?? '#' }}" class="inline-flex items-center px-8 py-4 font-bold text-white text-xs tracking-wider uppercase shadow-lg shadow-[var(--color-primary)]/20 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300" style="background-color: var(--color-primary); border-radius: var(--border-radius);">
                                <svg class="h-4 w-4 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397 0 11.977 0c3.187.001 6.185 1.24 8.437 3.496 2.25 2.257 3.488 5.259 3.485 8.448-.006 6.625-5.343 11.973-11.928 11.973-2.01-.001-3.987-.508-5.746-1.472L0 24z"/></svg>
                                {{ $section->content['button_text'] }}
                            </a>
                        @endif
                        <button onclick="document.getElementById('global-tracking-modal').classList.remove('hidden')" class="inline-flex items-center px-6 py-4 font-bold text-xs tracking-wider uppercase border-2 hover:bg-slate-50 transition-all duration-300 cursor-pointer" style="color: var(--color-primary); border-color: var(--color-primary); border-radius: var(--border-radius);">
                            📦 Lacak Pesanan
                        </button>
                    </div>

                    <!-- Global Tracking Modal (Lightweight modal using vanilla Tailwind/Alpine) -->
                    <div id="global-tracking-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm">
                        <div class="bg-white border border-[#E2E7EF] rounded-2xl max-w-md w-full overflow-hidden shadow-2xl relative" style="border-radius: calc(var(--border-radius) * 1.5);">
                            <div class="p-6 border-b border-[#E2E7EF] flex justify-between items-center bg-[#F8F9FC]">
                                <h3 class="text-sm font-black uppercase tracking-wider text-[#1E3A5F]">Lacak Status Laundry</h3>
                                <button onclick="document.getElementById('global-tracking-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 text-lg font-bold">✕</button>
                            </div>
                            <div class="p-6 space-y-4">
                                <p class="text-xs text-slate-500 font-medium leading-relaxed">Masukkan nomor invoice Anda untuk melacak progress cucian secara real-time.</p>
                                <form action="/track" method="GET" class="space-y-4">
                                    <div>
                                        <input name="invoice" type="text" placeholder="Masukkan nomor invoice (cth: INV-00001)" required
                                            class="w-full px-4 py-3 border border-[#E2E7EF] bg-slate-50 text-slate-800 placeholder-slate-400 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/10 transition-all" style="border-radius: var(--border-radius);">
                                    </div>
                                    <button type="submit" class="w-full py-3 font-bold text-white text-xs tracking-wider uppercase shadow-md transition-all duration-300 cursor-pointer" style="background-color: var(--color-primary); border-radius: var(--border-radius);">
                                        🔍 Periksa Status
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hero Visual Column -->
                <div class="lg:col-span-5 flex justify-center relative">
                    @if(isset($section->content['image_url']) && $section->content['image_url'])
                        <div class="relative group">
                            <div class="absolute -inset-4 bg-gradient-to-tr from-[var(--color-primary)]/20 to-[var(--color-accent)]/10 rounded-[2rem] blur-2xl opacity-60 group-hover:opacity-80 transition-opacity duration-500"></div>
                            <img src="{{ $section->content['image_url'] }}" alt="Hero Banner" class="relative max-w-full h-auto rounded-3xl shadow-2xl border border-slate-100/50 object-cover">
                        </div>
                    @else
                        <!-- Premium glassmorphic stats card -->
                        <div class="w-full max-w-sm relative">
                            <!-- Decorative blobs -->
                            <div class="absolute -top-8 -left-8 w-32 h-32 rounded-full blur-3xl opacity-40" style="background-color: var(--color-primary);"></div>
                            <div class="absolute -bottom-8 -right-8 w-40 h-40 rounded-full blur-3xl opacity-30" style="background-color: var(--color-accent);"></div>
                            
                            <!-- Main glassmorphic card -->
                            <div class="relative bg-white/80 backdrop-blur-xl border border-white/60 rounded-3xl shadow-2xl p-8 space-y-6">
                                <!-- Stats grid -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-gradient-to-br from-[var(--color-primary)] to-[var(--color-secondary)] p-5 rounded-2xl text-white text-center space-y-1 shadow-lg" style="box-shadow: 0 8px 30px color-mix(in srgb, var(--color-primary) 25%, transparent);">
                                        <span class="text-2xl font-black">24</span>
                                        <span class="text-[10px] uppercase tracking-widest font-bold block opacity-80">Jam Express</span>
                                    </div>
                                    <div class="bg-white border border-slate-100 p-5 rounded-2xl text-center space-y-1 shadow-sm">
                                        <span class="text-2xl font-black" style="color: var(--color-accent);">5★</span>
                                        <span class="text-[10px] uppercase tracking-widest font-bold block text-slate-400">Rating</span>
                                    </div>
                                    <div class="bg-white border border-slate-100 p-5 rounded-2xl text-center space-y-1 shadow-sm">
                                        <span class="text-2xl font-black" style="color: var(--color-primary);">1K+</span>
                                        <span class="text-[10px] uppercase tracking-widest font-bold block text-slate-400">Pelanggan</span>
                                    </div>
                                    <div class="bg-gradient-to-br from-[var(--color-accent)] to-amber-400 p-5 rounded-2xl text-white text-center space-y-1 shadow-lg">
                                        <span class="text-2xl font-black">🧺</span>
                                        <span class="text-[10px] uppercase tracking-widest font-bold block opacity-80">Gratis Antar</span>
                                    </div>
                                </div>
                                
                                <!-- Mini trust badge -->
                                <div class="flex items-center justify-center space-x-2 pt-2">
                                    <div class="flex -space-x-2">
                                        <div class="h-7 w-7 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 border-2 border-white flex items-center justify-center text-white text-[9px] font-bold">R</div>
                                        <div class="h-7 w-7 rounded-full bg-gradient-to-br from-pink-400 to-pink-600 border-2 border-white flex items-center justify-center text-white text-[9px] font-bold">S</div>
                                        <div class="h-7 w-7 rounded-full bg-gradient-to-br from-green-400 to-green-600 border-2 border-white flex items-center justify-center text-white text-[9px] font-bold">B</div>
                                    </div>
                                    <span class="text-[10px] font-bold text-slate-500">Dipercaya 1000+ pelanggan</span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        <!-- TRACKING / ORDER SEARCH SECTION -->
        @elseif($section->section_type === 'tracking')
            <div class="max-w-3xl mx-auto">
                <div class="text-center space-y-4 mb-10">
                    <h2 class="text-3xl md:text-4xl font-black tracking-tight" style="color: var(--color-heading); font-family: '{{ $theme->heading_font ?? 'Outfit' }}', sans-serif;">
                        {{ $section->content['title'] ?? 'Lacak Pesanan Laundry Anda' }}
                    </h2>
                    <div class="h-1 w-12 bg-gradient-to-r from-[var(--color-primary)] to-[var(--color-accent)] mx-auto rounded-full"></div>
                    <p class="text-xs md:text-sm font-medium opacity-80 max-w-lg mx-auto">
                        {{ $section->content['description'] ?? 'Masukkan nomor invoice untuk melihat status terkini pesanan laundry Anda secara real-time.' }}
                    </p>
                </div>
                
                <div class="bg-[var(--color-surface)] border border-[#E2E7EF]/80 p-8 md:p-10 shadow-xl relative overflow-hidden" style="border-radius: calc(var(--border-radius) * 1.5);">
                    <!-- Decorative accent -->
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-[var(--color-primary)] via-[var(--color-accent)] to-[var(--color-secondary)]"></div>
                    
                    <form action="/track" method="GET" class="space-y-5">
                        <div class="flex flex-col sm:flex-row gap-4">
                            <div class="flex-1 relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 opacity-40" style="color: var(--color-primary);" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </div>
                                <input name="invoice" type="text" placeholder="Masukkan nomor invoice (cth: INV-00001)" 
                                    class="w-full pl-12 pr-4 py-4 border border-[#E2E7EF] bg-[var(--color-background)] text-[var(--color-heading)] placeholder-slate-400 text-sm font-medium focus:outline-none focus:ring-2 transition-all" style="border-radius: var(--border-radius); --tw-ring-color: var(--color-primary);">
                            </div>
                            <button type="submit" class="px-8 py-4 font-bold text-white text-xs tracking-wider uppercase shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300 shrink-0" style="background-color: var(--color-primary); border-radius: var(--border-radius);">
                                🔍 Lacak Sekarang
                            </button>
                        </div>
                    </form>
                    
                    <!-- Trust indicators -->
                    <div class="flex flex-wrap items-center justify-center gap-6 mt-8 pt-6 border-t border-slate-100">
                        <div class="flex items-center space-x-2 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                            <span class="text-emerald-500">✓</span><span>Real-time Tracking</span>
                        </div>
                        <div class="flex items-center space-x-2 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                            <span class="text-emerald-500">✓</span><span>Notifikasi Status</span>
                        </div>
                        <div class="flex items-center space-x-2 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                            <span class="text-emerald-500">✓</span><span>Update Otomatis</span>
                        </div>
                    </div>
                </div>
            </div>

        <!-- SERVICES SECTION -->
        @elseif($section->section_type === 'services')
            @php
                $useMaster = $section->content['use_master_services'] ?? false;
                $displayServices = [];
                if ($useMaster) {
                    $displayServices = \App\Models\Tenant\Service::where('is_active', true)->take(8)->get();
                }
            @endphp
            
            <div class="text-center space-y-4 max-w-2xl mx-auto mb-16">
                <h2 class="text-3xl md:text-4xl font-black tracking-tight" style="color: var(--color-heading); font-family: '{{ $theme->heading_font ?? 'Outfit' }}', sans-serif;">
                    {{ $section->content['title'] ?? 'Layanan Unggulan Kami' }}
                </h2>
                <div class="h-1 w-12 bg-gradient-to-r from-[var(--color-primary)] to-[var(--color-accent)] mx-auto rounded-full"></div>
                <p class="text-xs md:text-sm font-medium opacity-90">
                    {{ $section->content['description'] ?? 'Pilih jenis paket cucian sesuai kebutuhan Anda.' }}
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @forelse($displayServices as $svc)
                    <div class="bg-[var(--color-surface)] border border-[#E2E7EF]/80 p-8 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between" style="border-radius: var(--border-radius);">
                        <div class="space-y-4">
                            <div class="h-12 w-12 bg-[var(--color-primary)]/10 text-[var(--color-primary)] rounded-2xl flex items-center justify-center text-xl font-bold">
                                🧺
                            </div>
                            <h3 class="text-sm font-bold text-[#1A1D23]" style="color: var(--color-heading);">{{ $svc->name }}</h3>
                            <p class="text-xs opacity-80 leading-relaxed font-medium">{{ $svc->description }}</p>
                        </div>
                        <span class="block text-xs font-black text-amber-500 mt-6 pt-4 border-t border-slate-100">
                            Rp {{ number_format($svc->price ?? 7000, 0, ',', '.') }}<span class="text-[10px] font-normal text-slate-400">/{{ $svc->unit ?? 'kg' }}</span>
                        </span>
                    </div>
                @empty
                    <!-- Fallback manual cards if no services present -->
                    <div class="bg-[var(--color-surface)] border border-[#E2E7EF]/80 p-8 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between" style="border-radius: var(--border-radius);">
                        <div class="space-y-4">
                            <div class="h-12 w-12 bg-[var(--color-primary)]/10 text-[var(--color-primary)] rounded-2xl flex items-center justify-center text-xl font-bold">
                                👕
                            </div>
                            <h3 class="text-sm font-bold text-[#1A1D23]" style="color: var(--color-heading);">Cuci Setrika Kiloan</h3>
                            <p class="text-xs opacity-80 leading-relaxed font-medium">Cuci kering lipat setrika rapi wangi parfum premium.</p>
                        </div>
                        <span class="block text-xs font-black text-amber-500 mt-6 pt-4 border-t border-slate-100">Rp 7.000<span class="text-[10px] font-normal text-slate-400">/kg</span></span>
                    </div>
                    <div class="bg-[var(--color-surface)] border border-[#E2E7EF]/80 p-8 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between" style="border-radius: var(--border-radius);">
                        <div class="space-y-4">
                            <div class="h-12 w-12 bg-[var(--color-primary)]/10 text-[var(--color-primary)] rounded-2xl flex items-center justify-center text-xl font-bold">
                                🧥
                            </div>
                            <h3 class="text-sm font-bold text-[#1A1D23]" style="color: var(--color-heading);">Cuci Satuan Premium</h3>
                            <p class="text-xs opacity-80 leading-relaxed font-medium">Cuci manual khusus jas, gaun, kebaya, atau jaket kulit.</p>
                        </div>
                        <span class="block text-xs font-black text-amber-500 mt-6 pt-4 border-t border-slate-100">Rp 20.000<span class="text-[10px] font-normal text-slate-400">/pcs</span></span>
                    </div>
                @endforelse
            </div>

        <!-- PRICING SECTION -->
        @elseif($section->section_type === 'pricing')
            <div class="text-center space-y-4 max-w-2xl mx-auto mb-16">
                <h2 class="text-3xl md:text-4xl font-black tracking-tight" style="color: var(--color-heading); font-family: '{{ $theme->heading_font ?? 'Outfit' }}', sans-serif;">
                    {{ $section->content['title'] ?? 'Daftar Paket Harga' }}
                </h2>
                <div class="h-1 w-12 bg-gradient-to-r from-[var(--color-primary)] to-[var(--color-accent)] mx-auto rounded-full"></div>
                <p class="text-xs md:text-sm font-medium opacity-90">
                    {{ $section->content['description'] ?? 'Pilih paket cuci terbaik dengan harga bersahabat.' }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($section->content['items'] ?? [] as $item)
                    <div class="bg-[var(--color-surface)] border border-[#E2E7EF]/80 p-8 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between relative group" style="border-radius: var(--border-radius);">
                        <div class="space-y-6">
                            <h3 class="text-md font-bold text-[#1A1D23]" style="color: var(--color-heading);">{{ $item['name'] }}</h3>
                            <div class="flex items-baseline text-3xl font-black text-amber-500 font-sans">
                                <span>{{ $item['price'] }}</span>
                                <span class="text-xs font-normal text-slate-400 ml-1.5">{{ $item['unit'] }}</span>
                            </div>
                            
                            <ul class="space-y-3.5 text-xs opacity-90 border-t border-slate-100 pt-6">
                                @foreach(explode(',', $item['features'] ?? '') as $feat)
                                    @if(trim($feat))
                                        <li class="flex items-center space-x-2.5 font-medium">
                                            <span class="text-emerald-500 font-bold text-sm">✓</span>
                                            <span>{{ trim($feat) }}</span>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>

                        <div class="pt-8">
                            <a href="#cta" class="block text-center px-4 py-3 text-xs font-extrabold text-white tracking-wider uppercase shadow-md hover:shadow-lg hover:bg-opacity-95 transition-all duration-200" style="background-color: var(--color-primary); border-radius: var(--border-radius);">
                                Pilih Paket
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

        <!-- TESTIMONIALS SECTION -->
        @elseif($section->section_type === 'testimonials')
            <div class="text-center space-y-4 max-w-2xl mx-auto mb-16">
                <h2 class="text-3xl md:text-4xl font-black tracking-tight" style="color: var(--color-heading); font-family: '{{ $theme->heading_font ?? 'Outfit' }}', sans-serif;">
                    {{ $section->content['title'] ?? 'Apa Kata Mereka?' }}
                </h2>
                <div class="h-1 w-12 bg-gradient-to-r from-[var(--color-primary)] to-[var(--color-accent)] mx-auto rounded-full"></div>
                <p class="text-xs md:text-sm font-medium opacity-90">
                    {{ $section->content['description'] ?? 'Ulasan jujur langsung dari pelanggan setia laundry kami.' }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @foreach($section->content['items'] ?? [] as $item)
                    <div class="bg-[var(--color-surface)] border border-[#E2E7EF]/80 p-8 shadow-sm hover:shadow-lg transition-all duration-300 flex flex-col justify-between" style="border-radius: var(--border-radius);">
                        <div class="space-y-4">
                            <div class="flex text-amber-400 text-sm">
                                @for($i = 0; $i < ($item['rating'] ?? 5); $i++)
                                    ★
                                @endfor
                            </div>
                            <p class="text-xs italic leading-relaxed opacity-95 font-medium text-slate-600">
                                "{{ $item['text'] }}"
                            </p>
                        </div>
                        <span class="block text-xs font-extrabold mt-6 pt-4 border-t border-slate-100" style="color: var(--color-heading);">
                            {{ $item['name'] }}
                        </span>
                    </div>
                @endforeach
            </div>

        <!-- FAQ SECTION -->
        @elseif($section->section_type === 'faq')
            <div class="max-w-3xl mx-auto space-y-8">
                <h2 class="text-3xl font-black text-center mb-10" style="color: var(--color-heading); font-family: '{{ $theme->heading_font ?? 'Outfit' }}', sans-serif;">
                    {{ $section->content['title'] ?? 'Pertanyaan Populer (FAQ)' }}
                </h2>

                <div x-data="{ active: null }" class="space-y-4">
                    @foreach($section->content['items'] ?? [] as $index => $item)
                        <div class="bg-[var(--color-surface)] border border-[#E2E7EF]/80 shadow-sm overflow-hidden" style="border-radius: var(--border-radius);">
                            <button @click="active = (active === {{ $index }} ? null : {{ $index }})" class="w-full p-5 flex items-center justify-between text-left font-bold text-xs" style="color: var(--color-heading);">
                                <span>{{ $item['question'] }}</span>
                                <span class="text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': active === {{ $index }} }">▼</span>
                            </button>
                            
                            <div x-show="active === {{ $index }}" 
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 -translate-y-2"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 class="p-5 border-t border-slate-100 text-xs leading-relaxed opacity-90 font-medium bg-slate-50/50">
                                {{ $item['answer'] }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        <!-- LOCATION SECTION -->
        @elseif($section->section_type === 'location')
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
                <div class="lg:col-span-5 space-y-6">
                    <h2 class="text-3xl md:text-4xl font-black tracking-tight" style="color: var(--color-heading); font-family: '{{ $theme->heading_font ?? 'Outfit' }}', sans-serif;">
                        {{ $section->content['title'] ?? 'Lokasi & Jam Kerja' }}
                    </h2>
                    
                    <div class="h-1 w-12 bg-gradient-to-r from-[var(--color-primary)] to-[var(--color-accent)] rounded-full"></div>
                    
                    <p class="text-xs md:text-sm leading-relaxed opacity-95 font-medium space-y-4">
                        <span class="block">📍 <b>Alamat Outlet:</b> <span class="block text-slate-500 mt-1">{{ $section->content['address'] ?? '' }}</span></span>
                        <span class="block">📞 <b>Nomor Telepon:</b> <span class="block text-slate-500 mt-1">{{ $section->content['phone'] ?? '' }}</span></span>
                        <span class="block">⏰ <b>Jam Operasional:</b> <span class="block text-slate-500 mt-1">{{ $section->content['hours'] ?? '' }}</span></span>
                    </p>
                </div>

                <!-- Google Map Embed Iframe -->
                <div class="lg:col-span-7 overflow-hidden border border-[#E2E7EF]/85 shadow-xl rounded-3xl h-96 relative bg-slate-100">
                            @php
                                $mapIframe = trim($section->content['map_iframe'] ?? '');
                                $isSafeIframe = preg_match('/^<iframe\s[^>]*src="https:\/\/(www\.)?google\.com\/maps\/embed\?[^"]+"[^>]*>\s*<\/iframe>$/i', $mapIframe);
                            @endphp
                            @if($isSafeIframe)
                                {!! $mapIframe !!}
                            @else
                                <div class="absolute inset-0 flex items-center justify-center text-xs font-semibold text-rose-500 bg-rose-50 p-6 text-center">
                                    Iframe peta tidak valid atau tidak aman (Hanya Google Maps resmi yang diizinkan).
                                </div>
                            @endif
                    @else
                        <div class="absolute inset-0 flex items-center justify-center text-xs font-semibold text-slate-400">
                            Peta Lokasi Outlet belum disematkan
                        </div>
                    @endif
                </div>
            </div>

        <!-- CTA SECTION -->
        @elseif($section->section_type === 'cta')
            <div class="bg-[var(--color-surface)] border border-[#E2E7EF]/80 p-10 md:p-16 text-center space-y-6 max-w-4xl mx-auto shadow-md" style="border-radius: var(--border-radius);">
                <h2 class="text-3xl font-black" style="color: var(--color-heading); font-family: '{{ $theme->heading_font ?? 'Outfit' }}', sans-serif;">
                    {{ $section->content['title'] ?? 'Siap Mencuci Pakaian Hari Ini?' }}
                </h2>
                <p class="text-xs md:text-sm opacity-90 max-w-2xl mx-auto leading-relaxed font-semibold">
                    {{ $section->content['text'] ?? 'Hubungi kami sekarang untuk penjemputan gratis ke alamat Anda!' }}
                </p>
                
                @php
                    $phone = preg_replace('/[^0-9]/', '', $section->content['whatsapp_number'] ?? '');
                    if (str_starts_with($phone, '08')) {
                        $phone = '628' . substr($phone, 2);
                    }
                    $message = urlencode($section->content['whatsapp_message'] ?? 'Halo, saya mau pesan laundry antar jemput.');
                    $waUrl = "https://api.whatsapp.com/send?phone={$phone}&text={$message}";
                @endphp
                
                <div class="pt-4">
                    <a href="{{ $waUrl }}" target="_blank" class="inline-block px-8 py-4 font-black text-white text-xs tracking-wider uppercase shadow-lg shadow-[var(--color-accent)]/15 hover:shadow-xl hover:shadow-[var(--color-accent)]/20 -translate-y-0.5 hover:-translate-y-1 transition-all duration-300" style="background-color: var(--color-accent); border-radius: var(--border-radius);">
                        {{ $section->content['button_text'] ?? 'Hubungi WhatsApp' }}
                    </a>
                </div>
            </div>
        @elseif($section->section_type === 'video')
            <div class="text-center space-y-4 max-w-2xl mx-auto mb-12">
                <h2 class="text-3xl md:text-4xl font-black tracking-tight" style="color: var(--color-heading); font-family: '{{ $theme->heading_font ?? 'Outfit' }}', sans-serif;">
                    {{ $section->content['title'] ?? 'Video Profil Kami' }}
                </h2>
                <div class="h-1 w-12 bg-gradient-to-r from-[var(--color-primary)] to-[var(--color-accent)] mx-auto rounded-full"></div>
            </div>

            <div class="max-w-4xl mx-auto overflow-hidden border border-[#E2E7EF]/80 shadow-2xl rounded-3xl bg-slate-950 aspect-video relative">
                @if(($section->content['video_type'] ?? 'youtube') === 'youtube')
                    @php
                        $youtubeUrl = $section->content['youtube_url'] ?? '';
                        $videoId = '';
                        if (preg_match('/embed\/([a-zA-Z0-9_-]+)/', $youtubeUrl, $matches)) {
                            $videoId = $matches[1];
                        } elseif (preg_match('/v=([a-zA-Z0-9_-]+)/', $youtubeUrl, $matches)) {
                            $videoId = $matches[1];
                        } elseif (preg_match('/youtu\.be\/([a-zA-Z0-9_-]+)/', $youtubeUrl, $matches)) {
                            $videoId = $matches[1];
                        } elseif (preg_match('/shorts\/([a-zA-Z0-9_-]+)/', $youtubeUrl, $matches)) {
                            $videoId = $matches[1];
                        }
                    @endphp
                    @if($videoId)
                        <iframe class="w-full h-full border-none" src="https://www.youtube.com/embed/{{ $videoId }}" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    @else
                        <div class="w-full h-full flex items-center justify-center text-xs text-slate-500">Tautan video YouTube tidak valid</div>
                    @endif
                @else
                    @if(isset($section->content['video_url']) && $section->content['video_url'])
                        <video class="w-full h-full object-cover" controls>
                            <source src="{{ $section->content['video_url'] }}" type="video/mp4">
                            Browser Anda tidak mendukung pemutaran video.
                        </video>
                    @else
                        <div class="w-full h-full flex items-center justify-center text-xs text-slate-500">Video belum diunggah</div>
                    @endif
                @endif
            </div>
        @endif

    </div>
</section>
