<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ tenancy()->initialized ? \App\Models\Tenant\Setting::getValue('laundry_name', 'KLIIN') : 'KLIIN' }} - Portal</title>
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#1E3A5F">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        body { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="h-full antialiased bg-[#F8F9FC] text-[#1A1D23]">
    <div class="min-h-full flex flex-col md:flex-row" x-data="{ sidebarOpen: false }">
        
        <!-- Mobile Sidebar Overlay -->
        <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-black/30 backdrop-blur-sm md:hidden" style="display:none"></div>

        <!-- Mobile Sidebar Drawer -->
        <aside x-show="sidebarOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
               x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
               class="fixed inset-y-0 left-0 z-50 w-64 flex flex-col bg-white border-r border-[#E2E7EF] shadow-2xl md:hidden" style="display:none">
            <div class="flex items-center justify-between h-16 px-6 border-b border-[#E2E7EF]">
                <span class="text-lg font-bold text-[#1E3A5F] tracking-wider">KLIIN</span>
                <button @click="sidebarOpen = false" class="text-[#8896A6] hover:text-[#1A1D23]">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                @include('layouts.partials.tenant-nav')
            </nav>
        </aside>

        <!-- Desktop Sidebar -->
        <aside class="hidden md:flex md:w-64 md:flex-col md:fixed md:inset-y-0 border-r border-[#E2E7EF] bg-white">
            <div class="flex-1 flex flex-col min-h-0">
                <div class="flex items-center h-16 flex-shrink-0 px-6 border-b border-[#E2E7EF]">
                    <div class="flex items-center space-x-2">
                        <div class="h-8 w-8 rounded-lg bg-gradient-to-br from-[#1E3A5F] to-[#2A5082] flex items-center justify-center">
                            <svg class="h-4 w-4 text-[#D4A853]" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 14a6 6 0 110-12 6 6 0 010 12z"/><path d="M10 5a1 1 0 011 1v3.586l2.707 2.707a1 1 0 01-1.414 1.414l-3-3A1 1 0 019 10V6a1 1 0 011-1z"/></svg>
                        </div>
                        <span class="text-lg font-bold text-[#1E3A5F] tracking-wider">KLIIN</span>
                    </div>
                </div>
                <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                    @include('layouts.partials.tenant-nav')
                </nav>
            </div>
        </aside>

        <!-- Main Section -->
        <div class="md:pl-64 flex flex-col flex-1">
            <!-- Topbar -->
            <header class="flex items-center justify-between h-16 px-6 border-b border-[#E2E7EF] bg-white/80 backdrop-blur-xl sticky top-0 z-30">
                <div class="flex items-center">
                    <button @click="sidebarOpen = true" class="md:hidden text-[#8896A6] hover:text-[#1A1D23] focus:outline-none mr-4">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <div class="flex items-center bg-[#F8F9FC] border border-[#E2E7EF] rounded-lg px-3 py-1.5 text-xs text-[#4A5568] font-medium">
                        <span class="inline-block h-2 w-2 rounded-full bg-emerald-500 mr-2 animate-pulse"></span>
                        {{ tenant('name') }}
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-semibold text-[#1A1D23]">{{ auth('tenant')->user()->name ?? 'Kasir' }}</p>
                        <p class="text-xs text-[#8896A6] capitalize">{{ auth('tenant')->user()->role->name ?? 'Staff' }}</p>
                    </div>
                    
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center focus:outline-none cursor-pointer">
                            <div class="h-9 w-9 rounded-full bg-gradient-to-br from-[#1E3A5F] to-[#2A5082] flex items-center justify-center font-bold text-white text-sm border-2 border-[#D4A853]/30 shadow-lg">
                                {{ strtoupper(substr(auth('tenant')->user()->name ?? 'K', 0, 1)) }}
                            </div>
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 rounded-xl bg-white border border-[#E2E7EF] shadow-2xl py-1 text-sm text-[#4A5568] z-50">
                            <a href="#" class="block px-4 py-2.5 hover:bg-[#F8F9FC] hover:text-[#1A1D23] transition-colors">Profil Saya</a>
                            <hr class="border-[#E2E7EF] my-1">
                            <a href="#" class="block px-4 py-2.5 hover:bg-rose-50 text-rose-500 font-semibold transition-colors" onclick="document.getElementById('logout-form').submit();">Keluar</a>
                            <form id="logout-form" action="{{ route('tenant.logout') }}" method="POST" class="hidden">@csrf</form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 py-8 px-6 overflow-y-auto">
                {{ $slot }}
            </main>
        </div>
    </div>

    @livewireScripts
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(reg => console.log('SW registered!', reg))
                    .catch(err => console.log('SW failed: ', err));
            });
        }
    </script>
</body>
</html>
