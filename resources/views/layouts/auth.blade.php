<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ tenancy()->initialized ? \App\Models\Tenant\Setting::getValue('laundry_name', 'Spinly') : 'Spinly' }} - Sign In</title>
    <link rel="icon" type="image/png" href="https://img.icons8.com/color/192/000000/washing-machine.png">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#0F1A2E">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        body { font-family: 'Outfit', sans-serif; }

        @keyframes float-slow {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(3deg); }
        }
        @keyframes float-medium {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-15px) rotate(-2deg); }
        }
        @keyframes pulse-glow {
            0%, 100% { opacity: 0.4; transform: scale(1); }
            50% { opacity: 0.7; transform: scale(1.05); }
        }
        @keyframes fade-in-up {
            from { opacity: 0; transform: translateY(24px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-float-slow { animation: float-slow 8s ease-in-out infinite; }
        .animate-float-medium { animation: float-medium 6s ease-in-out infinite; }
        .animate-pulse-glow { animation: pulse-glow 4s ease-in-out infinite; }
        .animate-fade-in-up { animation: fade-in-up 0.6s cubic-bezier(.16,1,.3,1) both; }
        .animate-fade-in-up-delay { animation: fade-in-up 0.6s cubic-bezier(.16,1,.3,1) 0.15s both; }
    </style>
</head>
<body class="h-full antialiased text-[#1A1D23]">
    <!-- Full-screen gradient background -->
    <div class="min-h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8 relative overflow-hidden"
         style="background: linear-gradient(135deg, #0F1A2E 0%, #162240 35%, #1A2D4A 65%, #0F1A2E 100%);">

        <!-- Decorative gradient orbs -->
        <div class="absolute top-[-10%] left-[-5%] w-[500px] h-[500px] rounded-full animate-pulse-glow"
             style="background: radial-gradient(circle, rgba(212,168,83,0.12) 0%, transparent 70%);"></div>
        <div class="absolute bottom-[-15%] right-[-8%] w-[600px] h-[600px] rounded-full animate-pulse-glow"
             style="background: radial-gradient(circle, rgba(16,185,129,0.08) 0%, transparent 70%); animation-delay: 2s;"></div>
        <div class="absolute top-[20%] right-[10%] w-[300px] h-[300px] rounded-full"
             style="background: radial-gradient(circle, rgba(59,130,246,0.06) 0%, transparent 70%);"></div>

        <!-- Floating geometric shapes -->
        <div class="absolute top-[15%] left-[8%] w-16 h-16 border border-white/[0.04] rounded-2xl animate-float-slow" style="transform: rotate(12deg);"></div>
        <div class="absolute top-[25%] right-[12%] w-10 h-10 border border-[#D4A853]/[0.08] rounded-xl animate-float-medium" style="animation-delay: 1s;"></div>
        <div class="absolute bottom-[20%] left-[15%] w-12 h-12 border border-[#10B981]/[0.06] rounded-lg animate-float-slow" style="animation-delay: 3s;"></div>
        <div class="absolute bottom-[30%] right-[20%] w-8 h-8 bg-white/[0.02] rounded-full animate-float-medium" style="animation-delay: 2s;"></div>

        <!-- Subtle grid pattern overlay -->
        <div class="absolute inset-0 opacity-[0.015]"
             style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 40px 40px;"></div>

        <!-- Top gradient accent line -->
        <div class="absolute top-0 left-0 right-0 h-[2px] bg-gradient-to-r from-transparent via-[#D4A853]/40 to-transparent"></div>

        <!-- Content -->
        <div class="relative z-10 animate-fade-in-up">
            {{ $slot }}
        </div>

        <!-- Footer -->
        <div class="relative z-10 mt-10 text-center animate-fade-in-up-delay">
            <p class="text-[11px] text-white/20 font-medium tracking-wide">
                &copy; {{ date('Y') }} Spinly &mdash; Platform Manajemen Laundry Pintar
            </p>
        </div>
    </div>

    <!-- Toast notification for auth pages (error from Google OAuth, etc.) -->
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
