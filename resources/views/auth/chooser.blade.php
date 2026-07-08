<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pilih Area Kerja — KLIIN</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body { font-family: 'Outfit', sans-serif; }</style>
</head>
<body class="h-full antialiased bg-[#F8F9FC] text-[#1A1D23]">
    <div class="min-h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8 relative">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_80%_50%_at_50%_-20%,rgba(30,58,95,0.06),transparent)]"></div>
        
        <div class="relative z-10 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="flex justify-center">
                <div class="flex items-center space-x-2">
                    <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-[#1E3A5F] to-[#2A5082] flex items-center justify-center shadow-lg shadow-[#1E3A5F]/20">
                        <svg class="h-5 w-5 text-[#D4A853]" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 14a6 6 0 110-12 6 6 0 010 12z"/><path d="M10 5a1 1 0 011 1v3.586l2.707 2.707a1 1 0 01-1.414 1.414l-3-3A1 1 0 019 10V6a1 1 0 011-1z"/></svg>
                    </div>
                    <span class="text-xl font-bold text-[#1E3A5F] tracking-wider">KLIIN</span>
                </div>
            </div>
            <h2 class="mt-6 text-center text-2xl font-extrabold text-[#1A1D23] tracking-tight">Pilih Area Kerja</h2>
            <p class="mt-2 text-center text-sm text-[#8896A6]">Akun Anda memiliki akses ganda. Pilih panel tujuan:</p>
        </div>

        <div class="relative z-10 mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-6 border border-[#E2E7EF] shadow-xl shadow-[#1E3A5F]/5 sm:rounded-2xl space-y-4">
                
                <a href="{{ url('/admin') }}" class="block text-left p-5 rounded-xl border border-[#E2E7EF] bg-white hover:bg-[#F8F9FC] hover:border-[#1E3A5F]/20 hover:shadow-lg transition-all group">
                    <div class="flex items-center space-x-3">
                        <div class="h-10 w-10 rounded-lg bg-[#1E3A5F]/5 text-[#1E3A5F] flex items-center justify-center group-hover:bg-[#1E3A5F] group-hover:text-white transition-all">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-[#1A1D23] group-hover:text-[#1E3A5F] transition-colors">Super Admin Panel</h3>
                            <p class="text-xs text-[#8896A6] mt-0.5">Kelola platform SaaS, tenant, dan pengaturan global.</p>
                        </div>
                    </div>
                </a>

                @php
                    $host = request()->getHost();
                    if ($host === '127.0.0.1') $host = 'localhost';
                    $port = request()->getPort() ? ':' . request()->getPort() : '';
                    $tenantUrl = 'http://' . $subdomain . '.' . $host . $port . '/login?email=' . urlencode($user->email);
                @endphp
                <a href="{{ $tenantUrl }}" class="block text-left p-5 rounded-xl border border-[#E2E7EF] bg-white hover:bg-[#F8F9FC] hover:border-[#D4A853]/30 hover:shadow-lg transition-all group">
                    <div class="flex items-center space-x-3">
                        <div class="h-10 w-10 rounded-lg bg-[#D4A853]/10 text-[#D4A853] flex items-center justify-center group-hover:bg-[#D4A853] group-hover:text-white transition-all">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-[#1A1D23] group-hover:text-[#D4A853] transition-colors">Laundry Saya (Tenant POS)</h3>
                            <p class="text-xs text-[#8896A6] mt-0.5">POS kasir, timbangan IoT, AI scan, dan kelola staf.</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</body>
</html>
