<div>
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="flex justify-center">
            <div class="flex items-center space-x-2">
                <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-[#1E3A5F] to-[#2A5082] flex items-center justify-center shadow-lg shadow-[#1E3A5F]/20">
                    <svg class="h-5 w-5 text-[#D4A853]" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 14a6 6 0 110-12 6 6 0 010 12z"/><path d="M10 5a1 1 0 011 1v3.586l2.707 2.707a1 1 0 01-1.414 1.414l-3-3A1 1 0 019 10V6a1 1 0 011-1z"/></svg>
                </div>
                <span class="text-xl font-bold text-[#1E3A5F] tracking-wider">KLIIN</span>
            </div>
        </div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-[#1A1D23] tracking-tight">Daftar Akun Baru</h2>
        <p class="mt-2 text-center text-sm text-[#8896A6]">
            Mulai kelola bisnis laundry Anda secara pintar
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 border border-[#E2E7EF] shadow-xl shadow-[#1E3A5F]/5 sm:rounded-2xl sm:px-10 space-y-6">
            
            <!-- Promo/Trial Banner -->
            <div class="bg-gradient-to-r from-[#1E3A5F]/5 to-[#D4A853]/5 border border-[#1E3A5F]/10 p-4 rounded-xl flex items-start space-x-3">
                <div class="text-[#D4A853] mt-0.5">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-[#1E3A5F] uppercase tracking-wider">Free Trial Premium</h4>
                    <p class="text-xs text-[#4A5568] mt-0.5">Dapatkan akses penuh ke seluruh fitur KLIIN Premium gratis selama 3 hari pertama setelah pendaftaran.</p>
                </div>
            </div>

            @if($errorMessage)
                <div class="bg-rose-50 border border-rose-200 text-rose-600 p-4 rounded-xl text-xs font-medium">
                    {{ $errorMessage }}
                </div>
            @endif

            <form wire:submit.prevent="register" class="space-y-5">
                <div>
                    <label for="fullName" class="block text-sm font-medium text-[#4A5568]">Nama Lengkap Anda</label>
                    <div class="mt-1">
                        <input wire:model="fullName" id="fullName" type="text" required 
                            class="appearance-none block w-full px-4 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] placeholder-[#8896A6] rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]/10 focus:border-[#1E3A5F] text-sm transition-all" placeholder="Ikram Dinata">
                    </div>
                    @error('fullName') <p class="mt-2 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-[#4A5568]">Email</label>
                    <div class="mt-1">
                        <input wire:model="email" id="email" type="email" required 
                            class="appearance-none block w-full px-4 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] placeholder-[#8896A6] rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]/10 focus:border-[#1E3A5F] text-sm transition-all" placeholder="nama@email.com">
                    </div>
                    @error('email') <p class="mt-2 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-[#4A5568]">Nomor WhatsApp</label>
                    <div class="mt-1">
                        <input wire:model="phone" id="phone" type="text" required 
                            class="appearance-none block w-full px-4 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] placeholder-[#8896A6] rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]/10 focus:border-[#1E3A5F] text-sm transition-all" placeholder="08123456789">
                    </div>
                    @error('phone') <p class="mt-2 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                <div class="border-t border-[#E2E7EF] pt-4 my-2"></div>

                <div>
                    <label for="laundryName" class="block text-sm font-medium text-[#4A5568]">Nama Bisnis Laundry</label>
                    <div class="mt-1">
                        <input wire:model="laundryName" id="laundryName" type="text" required 
                            class="appearance-none block w-full px-4 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] placeholder-[#8896A6] rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]/10 focus:border-[#1E3A5F] text-sm transition-all" placeholder="Barokah Laundry">
                    </div>
                    @error('laundryName') <p class="mt-2 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="subdomain" class="block text-sm font-medium text-[#4A5568]">Subdomain Toko</label>
                    <div class="mt-1 flex rounded-xl shadow-sm">
                        <input wire:model.live="subdomain" id="subdomain" type="text" required 
                            class="appearance-none block w-full min-w-0 flex-1 px-4 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] placeholder-[#8896A6] rounded-l-xl focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]/10 focus:border-[#1E3A5F] text-sm transition-all" placeholder="barokah">
                        <span class="inline-flex items-center px-4 rounded-r-xl border border-l-0 border-[#E2E7EF] bg-[#F8F9FC] text-[#8896A6] text-xs font-semibold">
                            {{ in_array(request()->getHost(), ['localhost', '127.0.0.1']) ? '.' : '-' }}{{ request()->getHost() }}{{ request()->getPort() && !in_array(request()->getPort(), [80, 443]) ? ':' . request()->getPort() : '' }}
                        </span>
                    </div>
                    <p class="mt-1.5 text-[11px] text-[#8896A6]">Alamat akses toko Anda nantinya: <span class="font-bold text-[#1E3A5F]">{{ $subdomain ?: '...' }}{{ in_array(request()->getHost(), ['localhost', '127.0.0.1']) ? '.' : '-' }}{{ request()->getHost() }}{{ request()->getPort() && !in_array(request()->getPort(), [80, 443]) ? ':' . request()->getPort() : '' }}</span></p>
                    @error('subdomain') <p class="mt-2 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                <div class="border-t border-[#E2E7EF] pt-4 my-2"></div>

                <div>
                    <label for="password" class="block text-sm font-medium text-[#4A5568]">Password</label>
                    <div class="mt-1">
                        <input wire:model="password" id="password" type="password" required 
                            class="appearance-none block w-full px-4 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] placeholder-[#8896A6] rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]/10 focus:border-[#1E3A5F] text-sm transition-all">
                    </div>
                    @error('password') <p class="mt-2 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-[#4A5568]">Konfirmasi Password</label>
                    <div class="mt-1">
                        <input wire:model="password_confirmation" id="password_confirmation" type="password" required 
                            class="appearance-none block w-full px-4 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] placeholder-[#8896A6] rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]/10 focus:border-[#1E3A5F] text-sm transition-all">
                    </div>
                </div>

                <div>
                    <button type="submit" 
                        class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white bg-gradient-to-r from-[#1E3A5F] to-[#2A5082] hover:from-[#2A5082] hover:to-[#1E3A5F] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#1E3A5F] transition-all cursor-pointer shadow-lg shadow-[#1E3A5F]/10">
                        Daftar & Mulai Trial Premium
                    </button>
                </div>
            </form>

            <div class="mt-6 border-t border-[#E2E7EF] pt-4 text-center">
                <span class="text-xs text-[#8896A6]">Sudah memiliki akun?</span>
                <a href="{{ route('login') }}" class="text-xs font-semibold text-[#1E3A5F] hover:text-[#2A5082] ml-1 transition-colors">Masuk di sini</a>
            </div>
        </div>
    </div>
</div>
