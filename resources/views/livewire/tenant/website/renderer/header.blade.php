@php
    $headerMenus = \App\Models\Tenant\NavigationMenu::where('menu_type', 'header')
        ->whereNull('parent_id')
        ->where('status', 'active')
        ->with('children')
        ->orderBy('sort_order')
        ->get();
@endphp

<header x-data="{ mobileMenuOpen: false }" class="bg-[var(--color-surface)]/90 backdrop-blur-md border-b border-[#E2E7EF]/60 sticky top-0 z-40 transition-all duration-300">
    <div class="max-w-6xl mx-auto px-6 h-20 flex items-center justify-between">
        
        <!-- Logo / Brand Name -->
        <a href="/" class="flex items-center space-x-2.5 group">
            @php
                $theme = \App\Models\Tenant\LandingThemeSetting::getSettings();
            @endphp
            @if(isset($theme->custom_settings['header']['logo_url']) && $theme->custom_settings['header']['logo_url'])
                <img src="{{ $theme->custom_settings['header']['logo_url'] }}" style="max-width: {{ $theme->custom_settings['header']['logo_width'] ?? '120px' }};" class="h-auto transition-transform group-hover:scale-105 duration-300">
            @else
                <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-[var(--color-primary)] to-[var(--color-secondary)] flex items-center justify-center shadow-md shadow-[var(--color-primary)]/10 transition-transform group-hover:scale-105 duration-300">
                    <svg class="h-5 w-5 text-[var(--color-accent)]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m12.728 12.728l.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/>
                    </svg>
                </div>
                <span class="text-lg font-black tracking-tight uppercase bg-clip-text text-transparent bg-gradient-to-r from-[var(--color-primary)] to-[var(--color-secondary)] font-sans">
                    {{ $theme->custom_settings['header']['business_name'] ?? tenant('name') ?? 'Laundry Kami' }}
                </span>
            @endif
        </a>

        <!-- Desktop Navigation Menu -->
        <nav class="hidden md:flex items-center space-x-8 text-xs font-bold uppercase tracking-wider">
            @foreach($headerMenus as $menu)
                @if($menu->children->isNotEmpty())
                    <!-- Submenu Dropdown -->
                    <div x-data="{ open: false }" @click.away="open = false" class="relative">
                        <button @click="open = !open" class="flex items-center space-x-1.5 transition-colors duration-200 hover:text-[var(--color-primary)] focus:outline-none" style="color: var(--color-text);">
                            <span>{{ $menu->label }}</span>
                            <svg class="h-3 w-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             class="absolute left-0 mt-3 w-52 bg-white border border-[#E2E7EF]/80 rounded-2xl shadow-xl p-2.5 space-y-1 z-50">
                            @foreach($menu->children as $child)
                                <a href="{{ $child->url }}" target="{{ $child->target }}" class="block px-4 py-2.5 text-xs font-semibold rounded-xl text-slate-700 hover:bg-slate-50 hover:text-[var(--color-primary)] transition-all">
                                    {{ $child->label }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @else
                    <a href="{{ $menu->url }}" target="{{ $menu->target }}" class="transition-colors duration-200 hover:text-[var(--color-primary)]" style="color: var(--color-text);">
                        {{ $menu->label }}
                    </a>
                @endif
            @endforeach
            
            <a href="{{ $theme->custom_settings['header']['cta_url'] ?? '#location' }}" class="px-5 py-2.5 text-[11px] font-extrabold text-white tracking-widest uppercase hover:opacity-90 shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300" style="background-color: var(--color-primary); border-radius: var(--border-radius);">
                {{ $theme->custom_settings['header']['cta_text'] ?? 'Hubungi Kami' }}
            </a>
        </nav>

        <!-- Mobile Menu Toggle Button -->
        <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 text-slate-500 hover:text-slate-800 focus:outline-none transition-colors">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

    </div>

    <!-- Mobile Navigation Drawer -->
    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="md:hidden border-t border-[#E2E7EF]/60 bg-[var(--color-surface)] py-5 px-6 space-y-4 shadow-inner">
        @foreach($headerMenus as $menu)
            @if($menu->children->isNotEmpty())
                <div class="space-y-2">
                    <span class="block text-[10px] font-black uppercase tracking-widest text-slate-400">{{ $menu->label }}</span>
                    @foreach($menu->children as $child)
                        <a href="{{ $child->url }}" target="{{ $child->target }}" class="block pl-4 py-1.5 text-xs font-bold text-slate-600 hover:text-[var(--color-primary)]">
                            {{ $child->label }}
                        </a>
                    @endforeach
                </div>
            @else
                <a href="{{ $menu->url }}" target="{{ $menu->target }}" class="block py-2 text-sm font-bold" style="color: var(--color-text);">
                    {{ $menu->label }}
                </a>
            @endif
        @endforeach
        
        <div class="pt-3">
            <a href="{{ $theme->custom_settings['header']['cta_url'] ?? '#location' }}" class="block text-center px-4 py-3 text-xs font-bold text-white shadow-md" style="background-color: var(--color-primary); border-radius: var(--border-radius);">
                {{ $theme->custom_settings['header']['cta_text'] ?? 'Hubungi Kami' }}
            </a>
        </div>
    </div>
</header>
