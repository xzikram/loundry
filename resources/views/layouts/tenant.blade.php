<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ tenancy()->initialized ? \App\Models\Tenant\Setting::getValue('laundry_name', 'Spinly') : 'Spinly' }} - Portal</title>
    <link rel="icon" type="image/png" href="https://img.icons8.com/color/192/000000/washing-machine.png">
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
               class="fixed inset-y-0 left-0 z-50 w-64 flex flex-col bg-gradient-to-b from-[#0F1A2E] via-[#162240] to-[#1A2D4A] border-r border-[#1E3A5F]/30 shadow-2xl md:hidden" style="display:none">
            <div class="flex items-center justify-between h-16 px-6 border-b border-white/5">
                <div class="flex items-center space-x-2.5">
                    <div class="h-8 w-8 rounded-lg bg-gradient-to-br from-[#D4A853] via-[#E8C97A] to-[#10B981] flex items-center justify-center shadow-lg shadow-[#D4A853]/25 shrink-0">
                        <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" d="M12 3a9 9 0 1 0 9 9c0-2-1.5-3.5-3-3s-3 1.5-3 3a3 3 0 1 1-6 0c0-1.5 1.2-3 3-3" />
                            <path d="M12 10.5l.3.7.7.3-.7.3-.3.7-.3-.7-.7-.3.7-.3z" fill="currentColor"/>
                        </svg>
                    </div>
                    <span class="text-lg font-black tracking-wider bg-clip-text text-transparent bg-gradient-to-r from-[#D4A853] to-[#E8C97A]">Spinly</span>
                </div>
                <button @click="sidebarOpen = false" class="text-slate-400 hover:text-white">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                @include('layouts.partials.tenant-nav')
            </nav>
            <!-- PWA Install Banner Mobile Sidebar -->
            <div id="pwa-install-container-mobile" style="display: none;" class="p-4 border-t border-white/5 shrink-0 bg-white/2">
                <div class="flex items-center space-x-2.5 mb-3">
                    <div class="h-8 w-8 rounded-lg bg-[#D4A853] flex items-center justify-center shrink-0 shadow-md">
                        <svg class="h-4.5 w-4.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-white">Instal Spinly App</h4>
                        <p class="text-[10px] text-slate-400">Akses cepat dari Home Screen</p>
                    </div>
                </div>
                <button id="pwa-install-btn-mobile" class="w-full py-2 bg-gradient-to-r from-[#D4A853] to-[#B8913A] text-white rounded-xl text-xs font-bold shadow-md shadow-[#D4A853]/10 transition-all cursor-pointer">
                    Pasang Aplikasi
                </button>
            </div>
        </aside>

        <!-- Desktop Sidebar -->
        <aside class="hidden md:flex md:w-64 md:flex-col md:fixed md:inset-y-0 bg-gradient-to-b from-[#0F1A2E] via-[#162240] to-[#1A2D4A] border-r border-[#1E3A5F]/30">
            <div class="flex-1 flex flex-col min-h-0">
                <div class="flex items-center h-16 flex-shrink-0 px-6 border-b border-white/5">
                    <div class="flex items-center space-x-2.5">
                        <div class="h-8 w-8 rounded-lg bg-gradient-to-br from-[#D4A853] via-[#E8C97A] to-[#10B981] flex items-center justify-center shadow-lg shadow-[#D4A853]/25 shrink-0">
                            <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" d="M12 3a9 9 0 1 0 9 9c0-2-1.5-3.5-3-3s-3 1.5-3 3a3 3 0 1 1-6 0c0-1.5 1.2-3 3-3" />
                                <path d="M12 10.5l.3.7.7.3-.7.3-.3.7-.3-.7-.7-.3.7-.3z" fill="currentColor"/>
                            </svg>
                        </div>
                        <span class="text-lg font-black tracking-wider bg-clip-text text-transparent bg-gradient-to-r from-[#D4A853] to-[#E8C97A]">Spinly</span>
                    </div>
                </div>
                <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                    @include('layouts.partials.tenant-nav')
                </nav>
                <!-- PWA Install Banner Desktop Sidebar -->
                <div id="pwa-install-container" style="display: none;" class="p-4 border-t border-white/5 shrink-0 bg-white/2">
                    <div class="flex items-center space-x-2.5 mb-3">
                        <div class="h-8 w-8 rounded-lg bg-[#D4A853] flex items-center justify-center shrink-0 shadow-md">
                            <svg class="h-4.5 w-4.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-white">Instal Spinly App</h4>
                            <p class="text-[10px] text-slate-400">Akses cepat dari Home Screen</p>
                        </div>
                    </div>
                    <button id="pwa-install-btn" class="w-full py-2 bg-gradient-to-r from-[#D4A853] to-[#B8913A] text-white rounded-xl text-xs font-bold shadow-md shadow-[#D4A853]/10 transition-all cursor-pointer">
                        Pasang Aplikasi
                    </button>
                </div>
            </div>
        </aside>

        <!-- Main Section -->
        <div class="md:pl-64 flex flex-col flex-1">
            <!-- Topbar -->
            <header class="flex items-center justify-between h-16 px-6 border-b border-[#E2E7EF] bg-white/80 backdrop-blur-xl sticky top-0 z-30 relative">
                <div class="absolute top-0 left-0 right-0 h-[2px] bg-gradient-to-r from-[#D4A853] via-[#10B981] to-[#3B82F6]"></div>
                <div class="flex items-center">
                    <button @click="sidebarOpen = true" class="md:hidden text-[#8896A6] hover:text-[#1A1D23] focus:outline-none mr-4">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <div class="flex items-center bg-gradient-to-r from-[#10B981]/5 to-[#D4A853]/5 border border-[#10B981]/15 rounded-lg px-3.5 py-1.5 text-xs text-[#1A1D23] font-semibold">
                        <span class="inline-block h-2 w-2 rounded-full bg-[#10B981] mr-2 animate-pulse shadow-sm shadow-[#10B981]/50"></span>
                        {{ tenant('name') }}
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-bold text-[#1A1D23]">{{ auth('tenant')->user()->name ?? 'Kasir' }}</p>
                        <p class="text-xs text-[#8896A6] capitalize font-medium">{{ auth('tenant')->user()->role->name ?? 'Staff' }}</p>
                    </div>
                    
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center focus:outline-none cursor-pointer">
                            <div class="h-9 w-9 rounded-full bg-gradient-to-br from-[#D4A853] to-[#10B981] flex items-center justify-center font-black text-white text-sm border-2 border-white shadow-lg shadow-[#D4A853]/15">
                                {{ strtoupper(substr(auth('tenant')->user()->name ?? 'K', 0, 1)) }}
                            </div>
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 rounded-xl bg-white border border-[#E2E7EF] shadow-2xl py-1 text-sm text-[#4A5568] z-50">
                            <a href="{{ route('tenant.profile') }}" class="block px-4 py-2.5 hover:bg-[#F8F9FC] hover:text-[#1A1D23] transition-colors font-medium">Profil Saya</a>
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

    <!-- Global Session Flash Notifications (Toast via JS Native) -->
    @if(session()->has('success') || session()->has('error') || session()->has('status'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var type = '{{ session()->has("error") ? "error" : "success" }}';
            var message = @json(session('success') ?? session('status') ?? session('error'));
            if (!message) return;

            var toast = document.createElement('div');
            toast.id = 'global-toast';
            toast.style.cssText = 'position:fixed;bottom:20px;right:20px;z-index:9999;max-width:380px;width:100%;padding:16px;border-radius:16px;display:flex;align-items:flex-start;gap:12px;box-shadow:0 25px 50px -12px rgba(0,0,0,.25);border:1px solid #E2E7EF;opacity:0;transform:translateY(12px);transition:all .4s cubic-bezier(.16,1,.3,1);';
            toast.style.background = 'white';

            var iconBg = type === 'error' ? 'rgba(244,63,94,.1)' : 'rgba(16,185,129,.1)';
            var iconColor = type === 'error' ? '#F43F5E' : '#10B981';
            var iconPath = type === 'error'
                ? 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'
                : 'M5 13l4 4L19 7';
            var title = type === 'error' ? 'Terjadi Kesalahan' : 'Berhasil';

            toast.innerHTML = '<div style="height:32px;width:32px;min-width:32px;border-radius:8px;background:'+iconBg+';display:flex;align-items:center;justify-content:center"><svg style="height:18px;width:18px;color:'+iconColor+'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="'+iconPath+'"/></svg></div>'
                + '<div style="flex:1;min-width:0"><h4 style="font-size:12px;font-weight:700;color:#1A1D23;margin:0">'+title+'</h4><p style="font-size:12px;color:#8896A6;margin:2px 0 0">'+message+'</p></div>'
                + '<button onclick="this.parentElement.style.opacity=0;setTimeout(function(){var t=document.getElementById(\'global-toast\');if(t)t.remove()},300)" style="background:none;border:none;cursor:pointer;color:#8896A6;padding:0;line-height:1"><svg style="height:16px;width:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>';

            document.body.appendChild(toast);
            requestAnimationFrame(function() {
                requestAnimationFrame(function() {
                    toast.style.opacity = '1';
                    toast.style.transform = 'translateY(0)';
                });
            });
            setTimeout(function() {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(12px)';
                setTimeout(function() { toast.remove(); }, 400);
            }, 5000);
        });
    </script>
    @endif

    <!-- Mobile PWA Floating Banner -->
    <div id="mobile-pwa-banner" style="display: none;" class="fixed bottom-4 left-4 right-4 z-40 bg-slate-900/95 backdrop-blur-md border border-white/10 rounded-2xl p-4 shadow-2xl flex items-center justify-between md:hidden">
        <div class="flex items-center space-x-3">
            <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-[#D4A853] via-[#E8C97A] to-[#10B981] flex items-center justify-center shrink-0 shadow-lg shadow-[#D4A853]/10">
                <svg style="height:20px;width:20px;color:white;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <h4 class="text-xs font-extrabold text-white">Aplikasi Spinly</h4>
                <p class="text-[10px] text-slate-400">Instal untuk akses lebih cepat</p>
            </div>
        </div>
        <div class="flex items-center space-x-2">
            <button id="mobile-pwa-close-btn" class="p-2 text-slate-400 hover:text-white cursor-pointer">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            <button id="mobile-pwa-install-btn" class="px-4 py-2 bg-gradient-to-r from-[#D4A853] to-[#B8913A] text-white rounded-xl text-xs font-bold shadow-md shadow-[#D4A853]/15 cursor-pointer">
                Instal
            </button>
        </div>
    </div>

    <!-- iOS Install Instructions Modal -->
    <div id="ios-install-modal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-xs px-4">
        <div class="bg-white border border-[#E2E7EF] rounded-3xl w-full max-w-sm p-6 shadow-2xl relative overflow-hidden space-y-4">
            <!-- Top Accent Line -->
            <div style="position: absolute; top: 0; left: 0; right: 0; height: 3px; background: linear-gradient(to right, #D4A853, #10B981, #3B82F6);"></div>

            <div class="flex justify-between items-center border-b border-[#E2E7EF] pb-2">
                <h3 class="text-md font-bold text-[#1A1D23]">Panduan Instalasi iOS</h3>
                <button onclick="document.getElementById('ios-install-modal').style.display='none'" class="text-[#8896A6] hover:text-[#1A1D23] cursor-pointer p-1 rounded-lg hover:bg-slate-100 transition-all">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="space-y-4 text-[#4A5568] text-xs leading-relaxed">
                <p class="text-center font-bold text-[#1E3A5F]">Aplikasi Spinly siap ditambahkan ke layar utama perangkat iOS Anda!</p>
                
                <div class="space-y-3.5 pt-2">
                    <div class="flex items-start space-x-3 bg-slate-50 p-3 rounded-2xl border border-slate-100">
                        <div class="h-6 w-6 rounded-lg bg-blue-500 text-white flex items-center justify-center font-bold text-xs shrink-0">1</div>
                        <p class="flex-1">Ketuk tombol **Bagikan (Share)** <svg style="display:inline;height:16px;width:16px;color:#2563eb;vertical-align:middle;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 10.742l5.028-2.514m0 0a3 3 0 10-3-3m3 3a3 3 0 103 3m-9.742 1.684l5.028 2.514m0 0a3 3 0 103 3m-3-3a3 3 0 10-3-3"/></svg> di Safari.</p>
                    </div>
                    
                    <div class="flex items-start space-x-3 bg-slate-50 p-3 rounded-2xl border border-slate-100">
                        <div class="h-6 w-6 rounded-lg bg-emerald-500 text-white flex items-center justify-center font-bold text-xs shrink-0">2</div>
                        <p class="flex-1">Pilih menu **Tambahkan ke Layar Utama (Add to Home Screen)** dari daftar pilihan.</p>
                    </div>

                    <div class="flex items-start space-x-3 bg-slate-50 p-3 rounded-2xl border border-slate-100">
                        <div class="h-6 w-6 rounded-lg bg-[#D4A853] text-white flex items-center justify-center font-bold text-xs shrink-0">3</div>
                        <p class="flex-1">Ketuk **Tambah (Add)** di sudut kanan atas untuk menyelesaikan instalasi.</p>
                    </div>
                </div>
            </div>

            <button onclick="document.getElementById('ios-install-modal').style.display='none'" class="w-full py-2.5 bg-gradient-to-r from-[#1E3A5F] to-[#2A5082] text-white rounded-xl text-xs font-bold shadow-md cursor-pointer hover:opacity-95">
                Saya Mengerti
            </button>
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

        // PWA Installation Prompt Handlers
        document.addEventListener('DOMContentLoaded', function() {
            let deferredPrompt;
            const pwaContainer = document.getElementById('pwa-install-container');
            const pwaContainerMobile = document.getElementById('pwa-install-container-mobile');
            const mobilePwaBanner = document.getElementById('mobile-pwa-banner');
            
            // Check if application is already running in standalone mode
            const isStandalone = window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true;

            if (isStandalone) {
                // App is already installed and opened as app, hide elements
                return;
            }

            // Detect iOS devices
            const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;

            if (isIOS) {
                // iOS does not fire beforeinstallprompt. We show the installation guides instead.
                if (!sessionStorage.getItem('pwa-dismissed')) {
                    if (pwaContainer) pwaContainer.style.display = 'block';
                    if (pwaContainerMobile) pwaContainerMobile.style.display = 'block';
                    if (mobilePwaBanner) mobilePwaBanner.style.display = 'flex';
                }
            }

            window.addEventListener('beforeinstallprompt', (e) => {
                e.preventDefault();
                deferredPrompt = e;
                
                // Show installation banner/buttons if they have not been dismissed in this session
                if (!sessionStorage.getItem('pwa-dismissed')) {
                    if (pwaContainer) pwaContainer.style.display = 'block';
                    if (pwaContainerMobile) pwaContainerMobile.style.display = 'block';
                    if (mobilePwaBanner) mobilePwaBanner.style.display = 'flex';
                }
            });

            const handleInstallAction = () => {
                if (isIOS) {
                    // Show custom premium guide modal for iOS Safari
                    const iosModal = document.getElementById('ios-install-modal');
                    if (iosModal) iosModal.style.display = 'flex';
                } else if (deferredPrompt) {
                    // Trigger native browser install prompt for Android/Chrome/Edge
                    deferredPrompt.prompt();
                    deferredPrompt.userChoice.then((choiceResult) => {
                        if (choiceResult.outcome === 'accepted') {
                            console.log('PWA installation accepted by user');
                            if (pwaContainer) pwaContainer.style.display = 'none';
                            if (pwaContainerMobile) pwaContainerMobile.style.display = 'none';
                            if (mobilePwaBanner) mobilePwaBanner.style.display = 'none';
                        }
                        deferredPrompt = null;
                    });
                }
            };

            // Bind click listeners
            document.getElementById('pwa-install-btn')?.addEventListener('click', handleInstallAction);
            document.getElementById('pwa-install-btn-mobile')?.addEventListener('click', handleInstallAction);
            document.getElementById('mobile-pwa-install-btn')?.addEventListener('click', handleInstallAction);

            // Bind close listener for mobile floating banner
            document.getElementById('mobile-pwa-close-btn')?.addEventListener('click', () => {
                if (mobilePwaBanner) mobilePwaBanner.style.display = 'none';
                sessionStorage.setItem('pwa-dismissed', 'true');
            });
        });
    </script>
</body>
</html>
