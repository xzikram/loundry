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
                        <span class="text-xs font-black uppercase tracking-widest block bg-clip-text text-transparent bg-gradient-to-r from-[var(--color-primary)] to-[var(--color-accent)]">
                            {{ $section->content['eyebrow'] }}
                        </span>
                    @endif
                    
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-black leading-tight tracking-tight" style="color: var(--color-heading); font-family: '{{ $theme->heading_font ?? 'Outfit' }}', sans-serif;">
                        {{ $section->content['title'] ?? 'Laundry Kilat Bersih' }}
                    </h1>
                    
                    <p class="text-sm md:text-base leading-relaxed opacity-90 max-w-2xl font-medium">
                        {{ $section->content['description'] ?? 'Tuliskan slogan utama outlet laundry Anda di sini.' }}
                    </p>
                    
                    @if(isset($section->content['button_text']) && $section->content['button_text'])
                        <div class="pt-3 flex flex-wrap gap-4">
                            <a href="{{ $section->content['button_url'] ?? '#' }}" class="inline-block px-8 py-4 font-bold text-white text-xs tracking-wider uppercase shadow-lg shadow-[var(--color-primary)]/15 hover:shadow-xl hover:shadow-[var(--color-primary)]/20 -translate-y-0.5 hover:-translate-y-1 transition-all duration-300" style="background-color: var(--color-primary); border-radius: var(--border-radius);">
                                {{ $section->content['button_text'] }}
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Hero Image Column -->
                <div class="lg:col-span-5 flex justify-center relative">
                    @if(isset($section->content['image_url']) && $section->content['image_url'])
                        <div class="relative group">
                            <div class="absolute inset-0 bg-gradient-to-tr from-[var(--color-primary)]/20 to-transparent rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            <img src="{{ $section->content['image_url'] }}" alt="Hero Banner" class="max-w-full h-auto rounded-3xl shadow-2xl border border-slate-100/50 object-cover">
                        </div>
                    @else
                        <!-- Premium illustration card layout -->
                        <div class="w-full aspect-square bg-gradient-to-tr from-[var(--color-primary)] to-[var(--color-secondary)] rounded-3xl flex flex-col items-center justify-center text-white relative shadow-2xl overflow-hidden p-8 border border-white/10 group">
                            <div class="absolute -right-16 -top-16 w-48 h-48 bg-white/5 rounded-full blur-2xl transition-transform group-hover:scale-110 duration-500"></div>
                            <div class="absolute -left-16 -bottom-16 w-48 h-48 bg-white/5 rounded-full blur-2xl transition-transform group-hover:scale-110 duration-500"></div>
                            
                            <div class="z-10 text-center space-y-4">
                                <div class="text-6xl animate-bounce duration-1000">👕</div>
                                <h4 class="text-lg font-black tracking-widest uppercase">KLIIN LAUNDRY</h4>
                                <p class="text-[11px] opacity-75 max-w-[200px] mx-auto leading-relaxed">Kebersihan terjamin, pakaian rapi, wangi premium tahan lama.</p>
                            </div>
                        </div>
                    @endif
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
                    @if(isset($section->content['map_iframe']) && $section->content['map_iframe'])
                        <div class="w-full h-full [&_iframe]:w-full [&_iframe]:h-full [&_iframe]:border-none">
                            {!! $section->content['map_iframe'] !!}
                        </div>
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
        @endif

    </div>
</section>
