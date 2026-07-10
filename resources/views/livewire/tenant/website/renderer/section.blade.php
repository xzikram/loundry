@php
    $bgColor = $section->settings['bg_color'] ?? '#FFFFFF';
    $textColor = $section->settings['text_color'] ?? '#4A5568';
    
    // Spacing mapping
    $paddings = [
        'none' => 'py-0',
        'small' => 'py-6',
        'medium' => 'py-16',
        'large' => 'py-24',
    ];
    $paddingTop = $paddings[$section->settings['padding_top'] ?? 'medium'] ?? 'py-16';
    $paddingBottom = $paddings[$section->settings['padding_bottom'] ?? 'medium'] ?? 'py-16';
@endphp

<section id="{{ $section->section_type }}" class="px-6 {{ $paddingTop }} {{ $paddingBottom }} transition-all" style="background-color: {{ $bgColor }}; color: {{ $textColor }};">
    <div class="max-w-6xl mx-auto">
        
        <!-- HERO SECTION -->
        @if($section->section_type === 'hero')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div class="space-y-6">
                    @if(isset($section->content['eyebrow']) && $section->content['eyebrow'])
                        <span class="text-xs font-bold uppercase tracking-widest block" style="color: var(--color-accent);">
                            {{ $section->content['eyebrow'] }}
                        </span>
                    @endif
                    
                    <h1 class="text-4xl md:text-5xl font-extrabold leading-tight" style="color: var(--color-heading); font-family: '{{ $theme->heading_font ?? 'Outfit' }}', sans-serif;">
                        {{ $section->content['title'] ?? 'Laundry Kilat Bersih' }}
                    </h1>
                    
                    <p class="text-sm md:text-base leading-relaxed opacity-90">
                        {{ $section->content['description'] ?? 'Tuliskan slogan utama outlet laundry Anda di sini.' }}
                    </p>
                    
                    @if(isset($section->content['button_text']) && $section->content['button_text'])
                        <div class="pt-2">
                            <a href="{{ $section->content['button_url'] ?? '#' }}" class="inline-block px-6 py-3 font-bold text-white text-xs shadow-md transition-all hover:scale-105" style="background-color: var(--color-primary); border-radius: var(--border-radius);">
                                {{ $section->content['button_text'] }}
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Hero Image -->
                <div class="flex justify-center">
                    @if(isset($section->content['image_url']) && $section->content['image_url'])
                        <img src="{{ $section->content['image_url'] }}" alt="Hero Banner" class="max-w-full h-auto rounded-2xl shadow-xl">
                    @else
                        <!-- Standalone beautiful fallback vector illustration -->
                        <div class="w-full aspect-video bg-gradient-to-tr from-[#1E3A5F] to-[#2A5082] rounded-2xl flex items-center justify-center text-white text-4xl shadow-xl">
                            👕🧺✨
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
            
            <div class="text-center space-y-4 max-w-2xl mx-auto mb-12">
                <h2 class="text-3xl font-bold" style="color: var(--color-heading); font-family: '{{ $theme->heading_font ?? 'Outfit' }}', sans-serif;">
                    {{ $section->content['title'] ?? 'Layanan Unggulan Kami' }}
                </h2>
                <p class="text-xs md:text-sm opacity-90">
                    {{ $section->content['description'] ?? 'Pilih jenis paket cucian sesuai kebutuhan Anda.' }}
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($displayServices as $svc)
                    <div class="bg-[var(--color-surface)] border border-[#E2E7EF] p-6 shadow-sm hover:shadow-md transition-all flex flex-col justify-between" style="border-radius: var(--border-radius);">
                        <div class="space-y-3">
                            <span class="text-2xl">🧺</span>
                            <h3 class="text-sm font-bold" style="color: var(--color-heading);">{{ $svc->name }}</h3>
                            <p class="text-xs opacity-80 leading-relaxed">{{ $svc->description }}</p>
                        </div>
                        <span class="block text-xs font-bold text-amber-500 mt-4">
                            Rp {{ number_format($svc->price ?? 7000, 0, ',', '.') }}<span class="text-[10px] font-normal text-slate-400">/{{ $svc->unit ?? 'kg' }}</span>
                        </span>
                    </div>
                @empty
                    <!-- Fallback manual cards if no services present -->
                    <div class="bg-[var(--color-surface)] border border-[#E2E7EF] p-6 shadow-sm flex flex-col justify-between" style="border-radius: var(--border-radius);">
                        <div class="space-y-3">
                            <span class="text-2xl">👕</span>
                            <h3 class="text-sm font-bold" style="color: var(--color-heading);">Cuci Setrika Kiloan</h3>
                            <p class="text-xs opacity-80 leading-relaxed">Cuci kering lipat setrika rapi wangi parfum premium.</p>
                        </div>
                        <span class="block text-xs font-bold text-amber-500 mt-4">Rp 7.000<span class="text-[10px] font-normal text-slate-400">/kg</span></span>
                    </div>
                    <div class="bg-[var(--color-surface)] border border-[#E2E7EF] p-6 shadow-sm flex flex-col justify-between" style="border-radius: var(--border-radius);">
                        <div class="space-y-3">
                            <span class="text-2xl">🧥</span>
                            <h3 class="text-sm font-bold" style="color: var(--color-heading);">Cuci Satuan Premium</h3>
                            <p class="text-xs opacity-80 leading-relaxed">Cuci manual khusus jas, gaun, kebaya, atau jaket kulit.</p>
                        </div>
                        <span class="block text-xs font-bold text-amber-500 mt-4">Rp 20.000<span class="text-[10px] font-normal text-slate-400">/pcs</span></span>
                    </div>
                @endforelse
            </div>

        <!-- PRICING SECTION -->
        @elseif($section->section_type === 'pricing')
            <div class="text-center space-y-4 max-w-2xl mx-auto mb-12">
                <h2 class="text-3xl font-bold" style="color: var(--color-heading); font-family: '{{ $theme->heading_font ?? 'Outfit' }}', sans-serif;">
                    {{ $section->content['title'] ?? 'Daftar Paket Harga' }}
                </h2>
                <p class="text-xs md:text-sm opacity-90">
                    {{ $section->content['description'] ?? 'Pilih paket cuci terbaik dengan harga bersahabat.' }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($section->content['items'] ?? [] as $item)
                    <div class="bg-[var(--color-surface)] border border-[#E2E7EF] p-8 shadow-sm flex flex-col justify-between relative group" style="border-radius: var(--border-radius);">
                        <div class="space-y-6">
                            <h3 class="text-md font-bold" style="color: var(--color-heading);">{{ $item['name'] }}</h3>
                            <div class="flex items-baseline text-2xl font-extrabold text-amber-500">
                                <span>{{ $item['price'] }}</span>
                                <span class="text-xs font-normal text-slate-400 ml-1">{{ $item['unit'] }}</span>
                            </div>
                            
                            <ul class="space-y-3 text-xs opacity-80 border-t border-slate-100 pt-4">
                                @foreach(explode(',', $item['features'] ?? '') as $feat)
                                    @if(trim($feat))
                                        <li class="flex items-center space-x-2">
                                            <span class="text-emerald-500">✓</span>
                                            <span>{{ trim($feat) }}</span>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>

                        <div class="pt-6">
                            <a href="#cta" class="block text-center px-4 py-2.5 text-xs font-bold text-white shadow-sm" style="background-color: var(--color-primary); border-radius: var(--border-radius);">
                                Pilih Paket
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

        <!-- TESTIMONIALS SECTION -->
        @elseif($section->section_type === 'testimonials')
            <div class="text-center space-y-4 max-w-2xl mx-auto mb-12">
                <h2 class="text-3xl font-bold" style="color: var(--color-heading); font-family: '{{ $theme->heading_font ?? 'Outfit' }}', sans-serif;">
                    {{ $section->content['title'] ?? 'Apa Kata Mereka?' }}
                </h2>
                <p class="text-xs md:text-sm opacity-90">
                    {{ $section->content['description'] ?? 'Ulasan jujur langsung dari pelanggan setia laundry kami.' }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @foreach($section->content['items'] ?? [] as $item)
                    <div class="bg-[var(--color-surface)] border border-[#E2E7EF] p-6 shadow-sm flex flex-col justify-between" style="border-radius: var(--border-radius);">
                        <div class="space-y-4">
                            <div class="flex text-amber-400 text-sm">
                                @for($i = 0; $i < ($item['rating'] ?? 5); $i++)
                                    ★
                                @endfor
                            </div>
                            <p class="text-xs italic leading-relaxed opacity-95">
                                "{{ $item['text'] }}"
                            </p>
                        </div>
                        <span class="block text-xs font-bold mt-4" style="color: var(--color-heading);">
                            {{ $item['name'] }}
                        </span>
                    </div>
                @endforeach
            </div>

        <!-- FAQ SECTION -->
        @elseif($section->section_type === 'faq')
            <div class="max-w-3xl mx-auto space-y-6">
                <h2 class="text-3xl font-bold text-center mb-10" style="color: var(--color-heading); font-family: '{{ $theme->heading_font ?? 'Outfit' }}', sans-serif;">
                    {{ $section->content['title'] ?? 'Pertanyaan Populer (FAQ)' }}
                </h2>

                <div x-data="{ active: null }" class="space-y-4">
                    @foreach($section->content['items'] ?? [] as $index => $item)
                        <div class="bg-[var(--color-surface)] border border-[#E2E7EF] shadow-sm overflow-hidden" style="border-radius: var(--border-radius);">
                            <button @click="active = (active === {{ $index }} ? null : {{ $index }})" class="w-full p-5 flex items-center justify-between text-left font-bold text-xs" style="color: var(--color-heading);">
                                <span>{{ $item['question'] }}</span>
                                <span x-show="active !== {{ $index }}">▼</span>
                                <span x-show="active === {{ $index }}">▲</span>
                            </button>
                            
                            <div x-show="active === {{ $index }}" class="p-5 border-t border-slate-100 text-xs leading-relaxed opacity-90" x-transition>
                                {{ $item['answer'] }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        <!-- LOCATION SECTION -->
        @elseif($section->section_type === 'location')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div class="space-y-6">
                    <h2 class="text-3xl font-bold" style="color: var(--color-heading); font-family: '{{ $theme->heading_font ?? 'Outfit' }}', sans-serif;">
                        {{ $section->content['title'] ?? 'Lokasi & Jam Kerja' }}
                    </h2>
                    
                    <p class="text-xs md:text-sm leading-relaxed opacity-95">
                        📍 <b>Alamat Outlet:</b> {{ $section->content['address'] ?? '' }}<br><br>
                        📞 <b>Nomor Telepon:</b> {{ $section->content['phone'] ?? '' }}<br><br>
                        ⏰ <b>Jam Operasional:</b> {{ $section->content['hours'] ?? '' }}
                    </p>
                </div>

                <!-- Google Map Embed Iframe -->
                <div class="overflow-hidden border border-[#E2E7EF] shadow-lg rounded-2xl h-80 relative bg-slate-100">
                    @if(isset($section->content['map_iframe']) && $section->content['map_iframe'])
                        {!! $section->content['map_iframe'] !!}
                    @else
                        <div class="absolute inset-0 flex items-center justify-center text-xs text-slate-400">
                            Peta Lokasi Outlet belum disematkan
                        </div>
                    @endif
                </div>
            </div>

        <!-- CTA SECTION -->
        @elseif($section->section_type === 'cta')
            <div class="bg-[var(--color-surface)] border border-[#E2E7EF] p-10 md:p-16 text-center space-y-6 max-w-4xl mx-auto shadow-sm" style="border-radius: var(--border-radius);">
                <h2 class="text-3xl font-bold" style="color: var(--color-heading); font-family: '{{ $theme->heading_font ?? 'Outfit' }}', sans-serif;">
                    {{ $section->content['title'] ?? 'Siap Mencuci Pakaian Hari Ini?' }}
                </h2>
                <p class="text-xs md:text-sm opacity-90 max-w-2xl mx-auto leading-relaxed">
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
                    <a href="{{ $waUrl }}" target="_blank" class="inline-block px-8 py-3.5 font-bold text-white text-xs shadow-md transition-all hover:scale-105" style="background-color: var(--color-accent); border-radius: var(--border-radius);">
                        {{ $section->content['button_text'] ?? 'Hubungi WhatsApp' }}
                    </a>
                </div>
            </div>
        @endif

    </div>
</section>
