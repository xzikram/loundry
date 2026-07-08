<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ tenancy()->initialized ? \App\Models\Tenant\Setting::getValue('laundry_name', 'KLIIN') : 'KLIIN' }} - Sign In</title>
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#1E3A5F">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>body { font-family: 'Outfit', sans-serif; }</style>
</head>
<body class="h-full antialiased bg-[#F8F9FC] text-[#1A1D23]">
    <div class="min-h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8 relative">
        <!-- Subtle Background Pattern -->
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_80%_50%_at_50%_-20%,rgba(30,58,95,0.06),transparent)]"></div>
        <div class="relative z-10">
            {{ $slot }}
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
