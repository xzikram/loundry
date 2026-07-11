<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lengkapi Registrasi — KLIIN</title>
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
            <h2 class="mt-6 text-center text-3xl font-extrabold text-[#1A1D23] tracking-tight">Lengkapi Registrasi</h2>
            <p class="mt-2 text-center text-sm text-[#8896A6]">Tinggal satu langkah lagi untuk memulai outlet Anda</p>
        </div>

        <div class="relative z-10 mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-6 border border-[#E2E7EF] shadow-xl shadow-[#1E3A5F]/5 sm:rounded-2xl sm:px-10">
                
                <!-- Google Account Card -->
                <div class="flex items-center space-x-3 p-3 bg-slate-50 border border-[#E2E7EF] rounded-xl mb-6">
                    <div class="h-10 w-10 rounded-full bg-[#1E3A5F]/10 flex items-center justify-center text-[#1E3A5F] font-bold text-lg">
                        {{ strtoupper(substr($name, 0, 1)) }}
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-sm font-semibold text-slate-800 truncate">{{ $name }}</p>
                        <p class="text-xs text-slate-500 truncate">{{ $email }}</p>
                    </div>
                </div>

                @if($errors->has('error'))
                    <div class="p-3 mb-4 rounded-lg bg-rose-50 border border-rose-200 text-xs text-rose-600">
                        {{ $errors->first('error') }}
                    </div>
                @endif

                <form action="{{ route('register.complete.submit') }}" method="POST" class="space-y-5">
                    @csrf

                    <div>
                        <label for="laundryName" class="block text-sm font-medium text-[#4A5568]">Nama Laundry</label>
                        <div class="mt-1">
                            <input id="laundryName" name="laundryName" type="text" value="{{ old('laundryName') }}" required placeholder="Contoh: Clean Max Laundry"
                                class="appearance-none block w-full px-4 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] placeholder-[#8896A6] rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]/10 focus:border-[#1E3A5F] text-sm transition-all @error('laundryName') border-rose-300 focus:border-rose-500 @enderror">
                        </div>
                        @error('laundryName') <p class="mt-2 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="subdomain" class="block text-sm font-medium text-[#4A5568]">Alamat Subdomain</label>
                        <div class="mt-1 flex rounded-xl shadow-sm">
                            <input id="subdomain" name="subdomain" type="text" value="{{ old('subdomain') }}" required placeholder="subdomain"
                                class="flex-1 min-w-0 block w-full px-4 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] placeholder-[#8896A6] rounded-l-xl focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]/10 focus:border-[#1E3A5F] text-sm transition-all @error('subdomain') border-rose-300 focus:border-rose-500 @enderror"
                                oninput="this.value = this.value.toLowerCase().replace(/[^a-z0-9-]/g, '')">
                            @php
                                $host = request()->getHost();
                                if ($host === '127.0.0.1') $host = 'localhost';
                                $separator = ($host === 'localhost' || $host === '127.0.0.1') ? '.' : '-';
                                $suffix = $separator . $host;
                            @endphp
                            <span class="inline-flex items-center px-3 rounded-r-xl border border-l-0 border-[#E2E7EF] bg-[#F1F3F9] text-[#8896A6] text-xs">
                                {{ $suffix }}
                            </span>
                        </div>
                        <p class="mt-1.5 text-2xs text-[#8896A6]">Hanya huruf kecil, angka, dan tanda strip (-).</p>
                        @error('subdomain') <p class="mt-2 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-[#4A5568]">Nomor Telepon</label>
                        <div class="mt-1">
                            <input id="phone" name="phone" type="tel" value="{{ old('phone') }}" required placeholder="Contoh: 081234567890"
                                class="appearance-none block w-full px-4 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] placeholder-[#8896A6] rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]/10 focus:border-[#1E3A5F] text-sm transition-all @error('phone') border-rose-300 focus:border-rose-500 @enderror">
                        </div>
                        @error('phone') <p class="mt-2 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <button type="submit" 
                            class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white bg-gradient-to-r from-[#1E3A5F] to-[#2A5082] hover:from-[#2A5082] hover:to-[#1E3A5F] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#1E3A5F] transition-all cursor-pointer shadow-lg shadow-[#1E3A5F]/10">
                            Selesaikan & Buat Outlet
                        </button>
                    </div>
                </form>

                <div class="mt-6 border-t border-[#E2E7EF] pt-4 text-center">
                    <a href="{{ route('register') }}" class="text-xs font-semibold text-[#8896A6] hover:text-[#1E3A5F] transition-colors">Batal & Kembali</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
