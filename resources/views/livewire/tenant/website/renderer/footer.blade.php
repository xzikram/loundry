@php
    $footerMenus = \App\Models\Tenant\NavigationMenu::where('menu_type', 'footer')
        ->whereNull('parent_id')
        ->where('status', 'active')
        ->orderBy('sort_order')
        ->get();
@endphp

<footer class="bg-slate-900 text-slate-400 py-12 px-6 border-t border-slate-800">
    <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-8">
        
        <!-- About/Brand column -->
        <div class="space-y-4">
            <h3 class="text-white text-lg font-bold">
                {{ tenant('name') ?? 'Laundry Kami' }}
            </h3>
            <p class="text-xs leading-relaxed">
                Layanan laundry berkualitas profesional untuk pakaian bersih, higienis, dan rapi setiap hari.
            </p>
        </div>

        <!-- Links column -->
        <div class="space-y-3">
            <h4 class="text-white text-xs font-bold uppercase tracking-wider">Tautan Cepat</h4>
            <ul class="space-y-2 text-xs">
                @foreach($footerMenus as $menu)
                    <li>
                        <a href="{{ $menu->url }}" target="{{ $menu->target }}" class="hover:text-white transition-all">
                            {{ $menu->label }}
                        </a>
                    </li>
                @endforeach
                @if($footerMenus->isEmpty())
                    <li><a href="#hero" class="hover:text-white">Beranda</a></li>
                    <li><a href="#services" class="hover:text-white">Layanan</a></li>
                    <li><a href="#pricing" class="hover:text-white">Harga</a></li>
                @endif
            </ul>
        </div>

        <!-- Contact/Operational Column -->
        <div class="space-y-3 col-span-2">
            <h4 class="text-white text-xs font-bold uppercase tracking-wider">Hubungi Kami</h4>
            <p class="text-xs leading-relaxed">
                📍 <b>Alamat Outlet:</b> {{ tenant('address') ?? 'Jl. Kebangsaan Raya No. 45' }}<br>
                📞 <b>WhatsApp Order:</b> {{ tenant('whatsapp') ?? '0812-3456-7890' }}<br>
                ⏰ <b>Jam Layanan:</b> 08:00 - 21:00 (Senin - Minggu)
            </p>
        </div>

    </div>

    <!-- Copyright Bar -->
    <div class="max-w-6xl mx-auto border-t border-slate-800 mt-8 pt-6 text-center text-[10px] text-slate-500">
        <p>&copy; {{ date('Y') }} {{ tenant('name') ?? 'Laundry Kami' }}. Dilindungi Undang-Undang. Powered by SaaS Laundry Builder.</p>
    </div>
</footer>
