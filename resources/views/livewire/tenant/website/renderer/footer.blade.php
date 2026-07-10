@php
    $footerMenus = \App\Models\Tenant\NavigationMenu::where('menu_type', 'footer')
        ->whereNull('parent_id')
        ->where('status', 'active')
        ->orderBy('sort_order')
        ->get();
@endphp

<footer class="bg-slate-950 text-slate-400 py-16 px-6 border-t border-slate-900 font-sans">
    <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-12 gap-10 md:gap-16">
        
        <!-- About/Brand column -->
        <div class="md:col-span-4 space-y-4">
            @php
                $theme = \App\Models\Tenant\LandingThemeSetting::getSettings();
            @endphp
            @if(isset($theme->custom_settings['footer']['logo_url']) && $theme->custom_settings['footer']['logo_url'])
                <img src="{{ $theme->custom_settings['footer']['logo_url'] }}" class="h-8 opacity-80 mb-2">
            @else
                <h3 class="text-white text-lg font-black tracking-tight uppercase">
                    {{ $theme->custom_settings['header']['business_name'] ?? tenant('name') ?? 'Laundry Kami' }}
                </h3>
            @endif
            <p class="text-xs leading-relaxed opacity-85 font-medium">
                {{ $theme->custom_settings['footer']['description'] ?? 'Layanan laundry premium terbaik yang mengutamakan kebersihan, wangi semerbak, dan ketepatan waktu cuci strika pakaian Anda.' }}
            </p>
        </div>

        <!-- Links column -->
        <div class="md:col-span-3 space-y-4">
            <h4 class="text-white text-xs font-bold uppercase tracking-widest text-slate-200">Navigasi Utama</h4>
            <ul class="space-y-2.5 text-xs font-medium">
                @foreach($footerMenus as $menu)
                    <li>
                        <a href="{{ $menu->url }}" target="{{ $menu->target }}" class="hover:text-[var(--color-accent)] hover:translate-x-1 inline-block transition-all duration-200">
                            {{ $menu->label }}
                        </a>
                    </li>
                @endforeach
                @if($footerMenus->isEmpty())
                    <li><a href="#hero" class="hover:text-[var(--color-accent)] hover:translate-x-1 inline-block transition-all">Beranda</a></li>
                    <li><a href="#services" class="hover:text-[var(--color-accent)] hover:translate-x-1 inline-block transition-all">Layanan</a></li>
                    <li><a href="#pricing" class="hover:text-[var(--color-accent)] hover:translate-x-1 inline-block transition-all">Harga</a></li>
                @endif
            </ul>
        </div>

        <!-- Contact/Operational Column -->
        <div class="md:col-span-5 space-y-4 text-xs font-medium">
            <h4 class="text-white text-xs font-bold uppercase tracking-widest text-slate-200">Hubungi Kami</h4>
            <div class="space-y-3 opacity-90 leading-relaxed">
                <p class="flex items-start space-x-2">
                    <span class="text-slate-500">📍</span>
                    <span><b>Alamat Outlet:</b> <span class="block text-slate-400 mt-0.5">{{ tenant('address') ?? 'Jl. Kebangsaan Raya No. 45' }}</span></span>
                </p>
                <p class="flex items-center space-x-2">
                    <span class="text-emerald-500">📞</span>
                    <span><b>WhatsApp:</b> <span class="text-slate-400">{{ tenant('whatsapp') ?? '0812-3456-7890' }}</span></span>
                </p>
                <p class="flex items-center space-x-2">
                    <span class="text-slate-500">⏰</span>
                    <span><b>Jam Kerja:</b> <span class="text-slate-400">08:00 - 21:00 (Senin - Minggu)</span></span>
                </p>
            </div>
        </div>

    </div>

    <!-- Copyright Bar -->
    <div class="max-w-6xl mx-auto border-t border-slate-900 mt-12 pt-8 text-center text-[10px] text-slate-600 font-bold uppercase tracking-wider">
        <p>{!! $theme->custom_settings['footer']['copyright'] ?? '&copy; ' . date('Y') . ' ' . (tenant('name') ?? 'Laundry Kami') . '. All rights reserved.' !!}</p>
    </div>
</footer>
