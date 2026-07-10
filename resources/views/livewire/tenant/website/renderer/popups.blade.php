@php
    $now = now();
    $activePopup = \App\Models\Tenant\PopupCampaign::where('is_active', true)
        ->where(function($q) use ($now) {
            $q->whereNull('start_at')->orWhere('start_at', '<=', $now);
        })
        ->where(function($q) use ($now) {
            $q->whereNull('end_at')->orWhere('end_at', '>=', $now);
        })
        ->first();
@endphp

@if($activePopup)
    @php
        $popupId = $activePopup->id;
        $popupType = $activePopup->popup_type;
        $content = $activePopup->content ?? [];
        $settings = $activePopup->settings ?? [];
        
        $triggerType = $activePopup->trigger_type;
        $triggerValue = $activePopup->trigger_value ?? '0';
        $frequencyType = $activePopup->frequency_type;
        
        $bgColor = $settings['bg_color'] ?? '#FFFFFF';
        $textColor = $settings['text_color'] ?? '#1A1D23';
    @endphp

    <div x-data="{ 
            show: false,
            init() {
                // Check frequency logic
                const storageKey = 'popup_campaign_' + {{ $popupId }};
                const freq = '{{ $frequencyType }}';
                const nowTime = new Date().getTime();
                
                let shouldShow = true;
                
                if (freq === 'only_once') {
                    if (localStorage.getItem(storageKey)) shouldShow = false;
                } else if (freq === 'once_per_session') {
                    if (sessionStorage.getItem(storageKey)) shouldShow = false;
                } else if (freq === 'once_per_day') {
                    const lastShown = localStorage.getItem(storageKey);
                    if (lastShown && (nowTime - parseInt(lastShown)) < 24 * 60 * 60 * 1000) shouldShow = false;
                } else if (freq === 'once_per_week') {
                    const lastShown = localStorage.getItem(storageKey);
                    if (lastShown && (nowTime - parseInt(lastShown)) < 7 * 24 * 60 * 60 * 1000) shouldShow = false;
                }
                
                if (!shouldShow) return;

                // Setup Trigger
                const trigger = '{{ $triggerType }}';
                const triggerVal = parseInt('{{ $triggerValue }}') || 0;

                if (trigger === 'immediately') {
                    this.openPopup();
                } else if (trigger === 'delay') {
                    setTimeout(() => this.openPopup(), triggerVal * 1000);
                } else if (trigger === 'scroll') {
                    window.addEventListener('scroll', () => {
                        const scrolled = (window.scrollY / (document.documentElement.scrollHeight - window.innerHeight)) * 100;
                        if (scrolled >= triggerVal && !this.show && !sessionStorage.getItem(storageKey)) {
                            this.openPopup();
                        }
                    });
                } else if (trigger === 'exit_intent') {
                    document.addEventListener('mouseleave', (e) => {
                        if (e.clientY < 20 && !this.show && !sessionStorage.getItem(storageKey)) {
                            this.openPopup();
                        }
                    });
                }
            },
            openPopup() {
                this.show = true;
                const storageKey = 'popup_campaign_' + {{ $popupId }};
                const nowTime = new Date().getTime();
                
                // Write frequency state
                localStorage.setItem(storageKey, nowTime.toString());
                sessionStorage.setItem(storageKey, 'shown');
                
                // Lock body scroll if overlay popup is shown
                if ('{{ $popupType }}' === 'center_modal' || '{{ $popupType }}' === 'full_screen') {
                    document.body.style.overflow = 'hidden';
                }
            },
            closePopup() {
                this.show = false;
                document.body.style.overflow = '';
            }
         }"
         x-show="show"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 select-none"
         style="display: none;">
        
        <!-- Background Overlay -->
        @if($popupType === 'center_modal' || $popupType === 'full_screen')
            <div @click="closePopup" class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
        @endif

        <!-- CENTER MODAL POPUP -->
        @if($popupType === 'center_modal')
            <div class="relative bg-white border max-w-md w-full overflow-hidden shadow-2xl z-10 flex flex-col"
                 style="background-color: {{ $bgColor }}; color: {{ $textColor }}; border-radius: var(--border-radius, 16px);">
                
                @if(isset($content['image_url']) && $content['image_url'])
                    <img src="{{ $content['image_url'] }}" alt="Promo Image" class="w-full h-48 object-cover">
                @endif
                
                <div class="p-6 space-y-4">
                    <h3 class="text-lg font-bold">{{ $content['title'] ?? 'Penawaran Spesial!' }}</h3>
                    <p class="text-xs leading-relaxed opacity-90">{{ $content['description'] ?? '' }}</p>
                    
                    @if(isset($content['button_text']) && $content['button_text'])
                        <a href="{{ $content['button_url'] ?? '#' }}" class="block text-center px-4 py-2.5 text-xs font-bold text-white shadow-sm" style="background-color: var(--color-primary); border-radius: var(--border-radius);">
                            {{ $content['button_text'] }}
                        </a>
                    @endif
                </div>

                <button @click="closePopup" class="absolute top-3 right-3 text-slate-400 hover:text-slate-700 bg-white/80 h-7 w-7 rounded-full flex items-center justify-center font-bold">✕</button>
            </div>

        <!-- FULL SCREEN INTERSTITIAL -->
        @elseif($popupType === 'full_screen')
            <div class="absolute inset-0 z-10 flex flex-col justify-center items-center p-8 text-center"
                 style="background-color: {{ $bgColor }}; color: {{ $textColor }};">
                
                <div class="max-w-xl space-y-6">
                    @if(isset($content['image_url']) && $content['image_url'])
                        <img src="{{ $content['image_url'] }}" alt="Promo" class="max-w-[280px] rounded-2xl mx-auto shadow-md">
                    @endif

                    <h2 class="text-3xl font-extrabold">{{ $content['title'] ?? '' }}</h2>
                    <p class="text-sm leading-relaxed opacity-90">{{ $content['description'] ?? '' }}</p>
                    
                    @if(isset($content['button_text']) && $content['button_text'])
                        <div class="pt-4">
                            <a href="{{ $content['button_url'] ?? '#' }}" class="inline-block px-8 py-3.5 font-bold text-white text-xs shadow-md" style="background-color: var(--color-primary); border-radius: var(--border-radius);">
                                {{ $content['button_text'] }}
                            </a>
                        </div>
                    @endif
                </div>

                <button @click="closePopup" class="absolute top-6 right-6 text-2xl font-bold bg-slate-100 hover:bg-slate-200 h-10 w-10 rounded-full flex items-center justify-center">✕</button>
            </div>

        <!-- BOTTOM BANNER -->
        @elseif($popupType === 'bottom_banner')
            <div class="fixed bottom-6 left-6 right-6 md:left-auto md:w-[400px] bg-white border border-slate-200 shadow-2xl p-5 rounded-2xl z-50 flex items-start space-x-4 transition-all"
                 style="background-color: {{ $bgColor }}; color: {{ $textColor }}; border-radius: var(--border-radius, 12px);">
                
                <div class="flex-1 space-y-3">
                    <h4 class="text-xs font-bold">{{ $content['title'] ?? '' }}</h4>
                    <p class="text-[10px] leading-relaxed opacity-95">{{ $content['description'] ?? '' }}</p>
                    
                    @if(isset($content['button_text']) && $content['button_text'])
                        <a href="{{ $content['button_url'] ?? '#' }}" class="inline-block px-3 py-1.5 text-[10px] font-bold text-white shadow-sm" style="background-color: var(--color-primary); border-radius: var(--border-radius);">
                            {{ $content['button_text'] }}
                        </a>
                    @endif
                </div>
                
                <button @click="closePopup" class="text-slate-400 hover:text-slate-700">✕</button>
            </div>

        <!-- SLIDE-IN RIGHT -->
        @elseif($popupType === 'slide_in_right')
            <div class="fixed bottom-6 right-6 md:w-[350px] bg-white border border-slate-200 shadow-2xl p-5 rounded-2xl z-50 flex flex-col space-y-4"
                 style="background-color: {{ $bgColor }}; color: {{ $textColor }}; border-radius: var(--border-radius, 12px);">
                
                <div class="flex justify-between items-center border-b pb-2">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Penawaran Menarik</span>
                    <button @click="closePopup" class="text-slate-400 hover:text-slate-700">✕</button>
                </div>

                <div class="space-y-3">
                    <h4 class="text-xs font-bold leading-tight">{{ $content['title'] ?? '' }}</h4>
                    <p class="text-[10px] leading-relaxed opacity-90">{{ $content['description'] ?? '' }}</p>
                    
                    @if(isset($content['button_text']) && $content['button_text'])
                        <a href="{{ $content['button_url'] ?? '#' }}" class="block text-center px-3 py-2 text-[10px] font-bold text-white shadow-sm" style="background-color: var(--color-primary); border-radius: var(--border-radius);">
                            {{ $content['button_text'] }}
                        </a>
                    @endif
                </div>
            </div>
        @endif

    </div>
@endif
