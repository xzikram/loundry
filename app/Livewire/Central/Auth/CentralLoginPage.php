<?php

namespace App\Livewire\Central\Auth;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Central\CentralUser;
use App\Models\Central\Tenant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

#[Layout('layouts.auth')]
class CentralLoginPage extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    protected array $rules = [
        'email' => 'required|email',
        'password' => 'required|string',
    ];

    public function login()
    {
        $this->validate();

        // Rate Limiting
        $throttleKey = strtolower($this->email) . '|' . request()->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            throw ValidationException::withMessages([
                'email' => __('Terlalu banyak percobaan. Coba lagi dalam :seconds detik.', ['seconds' => $seconds]),
            ]);
        }

        $superAdmin = CentralUser::where('email', $this->email)->first();
        $tenant = Tenant::where('email', $this->email)->first();

        if ($superAdmin) {
            if (Auth::guard('web')->attempt(['email' => $this->email, 'password' => $this->password, 'is_active' => true], $this->remember)) {
                request()->session()->regenerate();
                RateLimiter::clear($throttleKey);
                
                if ($tenant) {
                    return redirect()->to(route('login.chooser'));
                }
                return redirect()->to(url('/admin'));
            }
            RateLimiter::hit($throttleKey, 60);
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        if ($tenant) {
            $subdomain = $tenant->id;
            $host = request()->getHost();
            if ($host === '127.0.0.1') $host = 'localhost';
            
            // Check if there is an existing domain in database
            $domainModel = $tenant->domains()->first();
            $tenantDomain = $domainModel ? $domainModel->domain : '';
            
            if (!$tenantDomain) {
                // Fallback construction using the correct separator
                $separator = ($host === 'localhost' || $host === '127.0.0.1') ? '.' : '-';
                $port = request()->getPort() ? ':' . request()->getPort() : '';
                $tenantDomain = $subdomain . $separator . $host . $port;
            } else {
                $port = request()->getPort() ? ':' . request()->getPort() : '';
                if ($port && !str_contains($tenantDomain, ':')) {
                    $tenantDomain .= $port;
                }
            }
            
            $scheme = request()->getScheme();
            return redirect()->away($scheme . '://' . $tenantDomain . '/login?email=' . urlencode($this->email));
        }

        RateLimiter::hit($throttleKey, 60);
        throw ValidationException::withMessages([
            'email' => 'Email tidak terdaftar. Staf outlet silakan masuk melalui domain outlet Anda.',
        ]);
    }

    public function render()
    {
        return <<<'HTML'
        <div>
            <div class="sm:mx-auto sm:w-full sm:max-w-md">
                <div class="flex justify-center">
                    <div class="flex items-center space-x-2.5">
                        <div class="h-11 w-11 rounded-xl bg-gradient-to-br from-[#D4A853] via-[#E8C97A] to-[#10B981] flex items-center justify-center shadow-lg shadow-[#D4A853]/25">
                            <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <span class="text-2xl font-black tracking-wider bg-clip-text text-transparent bg-gradient-to-r from-[#D4A853] to-[#E8C97A]">KLIIN</span>
                    </div>
                </div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-white tracking-tight">Masuk ke Akun</h2>
                <p class="mt-2 text-center text-sm text-white/50">Super Admin atau Owner Outlet</p>
            </div>

            <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
                <div class="bg-white/95 backdrop-blur-xl py-8 px-4 border border-white/20 shadow-2xl shadow-black/20 sm:rounded-2xl sm:px-10">
                    @if(session()->has('error'))
                        <div class="mb-5 p-3.5 rounded-xl bg-rose-50 border border-rose-100 text-xs font-semibold text-rose-600">
                            {{ session('error') }}
                        </div>
                    @endif
                    <form wire:submit.prevent="login" class="space-y-6">
                        <div>
                            <label for="email" class="block text-sm font-medium text-[#4A5568]">Email</label>
                            <div class="mt-1">
                                <input wire:model="email" id="email" type="email" autocomplete="email" required 
                                    class="appearance-none block w-full px-4 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] placeholder-[#8896A6] rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]/10 focus:border-[#1E3A5F] text-sm transition-all">
                            </div>
                            @error('email') <p class="mt-2 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-[#4A5568]">Password</label>
                            <div class="mt-1">
                                <input wire:model="password" id="password" type="password" autocomplete="current-password" required 
                                    class="appearance-none block w-full px-4 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] placeholder-[#8896A6] rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]/10 focus:border-[#1E3A5F] text-sm transition-all">
                            </div>
                            @error('password') <p class="mt-2 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex items-center">
                            <input wire:model="remember" id="remember-me" type="checkbox" class="h-4 w-4 text-[#1E3A5F] focus:ring-[#1E3A5F]/20 border-[#E2E7EF] bg-[#F8F9FC] rounded">
                            <label for="remember-me" class="ml-2 block text-sm text-[#8896A6]">Ingat saya</label>
                        </div>

                        <div>
                            <button type="submit" 
                                class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white bg-gradient-to-r from-[#1E3A5F] to-[#2A5082] hover:from-[#2A5082] hover:to-[#1E3A5F] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#1E3A5F] transition-all cursor-pointer shadow-lg shadow-[#1E3A5F]/10">
                                Masuk
                            </button>
                        </div>
                    </form>

                    <div class="mt-6">
                        <div class="relative">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-[#E2E7EF]"></div>
                            </div>
                            <div class="relative flex justify-center text-xs uppercase">
                                <span class="bg-white px-2 text-[#8896A6]">Atau masuk dengan</span>
                            </div>
                        </div>

                        <div class="mt-6">
                            <a href="{{ route('auth.google') }}" class="w-full flex items-center justify-center gap-3 py-2.5 px-4 border border-[#E2E7EF] rounded-xl shadow-sm text-sm font-semibold text-[#4A5568] bg-[#F8F9FC] hover:bg-slate-50 hover:border-[#1E3A5F]/20 transition-all cursor-pointer">
                                <svg class="h-5 w-5" viewBox="0 0 24 24">
                                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z" fill="#FBBC05"/>
                                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                                </svg>
                                Google
                            </a>
                        </div>
                    </div>

                    <div class="mt-6 border-t border-[#E2E7EF] pt-4 text-center">
                        <span class="text-xs text-[#8896A6]">Belum memiliki akun?</span>
                        <a href="{{ route('register') }}" class="text-xs font-semibold text-[#1E3A5F] hover:text-[#2A5082] ml-1 transition-colors">Daftar Trial Gratis</a>
                    </div>
                </div>
            </div>
        </div>
        HTML;
    }
}
