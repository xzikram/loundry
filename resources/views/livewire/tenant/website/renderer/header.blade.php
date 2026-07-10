@php
    $headerMenus = \App\Models\Tenant\NavigationMenu::where('menu_type', 'header')
        ->whereNull('parent_id')
        ->where('status', 'active')
        ->with('children')
        ->orderBy('sort_order')
        ->get();
@endphp

<header x-data="{ mobileMenuOpen: false }" class="bg-[var(--color-surface)] border-b border-[#E2E7EF] sticky top-0 z-40 transition-all shadow-sm">
    <div class="max-w-6xl mx-auto px-6 h-20 flex items-center justify-between">
        
        <!-- Logo / Name -->
        <a href="/" class="text-xl font-bold tracking-tight" style="color: var(--color-primary);">
            {{ tenant('name') ?? 'Laundry Kami' }}
        </a>

        <!-- Desktop Navigation Menu -->
        <nav class="hidden md:flex items-center space-x-8 text-sm font-semibold">
            @foreach($headerMenus as $menu)
                @if($menu->children->isNotEmpty())
                    <!-- Submenu Dropdown -->
                    <div x-data="{ open: false }" @click.away="open = false" class="relative">
                        <button @click="open = !open" class="flex items-center space-x-1 hover:opacity-85" style="color: var(--color-text);">
                            <span>{{ $menu->label }}</span>
                            <span>▼</span>
                        </button>
                        <div x-show="open" class="absolute left-0 mt-2 w-48 bg-white border rounded-xl shadow-lg p-2 space-y-1 z-50">
                            @foreach($menu->children as $child)
                                <a href="{{ $child->url }}" target="{{ $child->target }}" class="block px-4 py-2 text-xs hover:bg-slate-50 rounded-lg text-slate-700">
                                    {{ $child->label }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @else
                    <a href="{{ $menu->url }}" target="{{ $menu->target }}" class="hover:opacity-85" style="color: var(--color-text);">
                        {{ $menu->label }}
                    </a>
                @endif
            @endforeach
            
            <a href="#location" class="px-5 py-2.5 text-xs font-bold text-white shadow-sm transition-all" style="background-color: var(--color-primary); border-radius: var(--border-radius);">
                Hubungi Kami
            </a>
        </nav>

        <!-- Mobile Menu Toggle Button -->
        <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 text-slate-600 focus:outline-none">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

    </div>

    <!-- Mobile Navigation Drawer -->
    <div x-show="mobileMenuOpen" class="md:hidden border-t border-[#E2E7EF] bg-[var(--color-surface)] py-4 px-6 space-y-3 shadow-inner" x-transition>
        @foreach($headerMenus as $menu)
            @if($menu->children->isNotEmpty())
                <div class="space-y-2">
                    <span class="block text-xs font-bold uppercase tracking-wider text-slate-400">{{ $menu->label }}</span>
                    @foreach($menu->children as $child)
                        <a href="{{ $child->url }}" target="{{ $child->target }}" class="block pl-4 py-1 text-sm text-slate-600">
                            {{ $child->label }}
                        </a>
                    @endforeach
                </div>
            @else
                <a href="{{ $menu->url }}" target="{{ $menu->target }}" class="block py-2 text-sm font-semibold" style="color: var(--color-text);">
                    {{ $menu->label }}
                </a>
            @endif
        @endforeach
        
        <div class="pt-2">
            <a href="#location" class="block text-center px-4 py-2.5 text-xs font-bold text-white shadow-sm" style="background-color: var(--color-primary); border-radius: var(--border-radius);">
                Hubungi Kami
            </a>
        </div>
    </div>
</header>
