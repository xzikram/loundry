<?php

namespace App\Livewire\Tenant\Auth;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

#[Layout('layouts.auth')]
class LoginPage extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    public function mount()
    {
        $ssoToken = request()->query('sso_token');
        if ($ssoToken) {
            $centralUser = tenancy()->central(function () use ($ssoToken) {
                return \App\Models\Central\CentralUser::where('sso_token', $ssoToken)
                    ->where('sso_token_expires_at', '>', now())
                    ->first();
            });

            if ($centralUser) {
                // Find matching active tenant user in this tenant database
                $tenantUser = \App\Models\Tenant\User::where('email', $centralUser->email)
                    ->where('is_active', true)
                    ->first();

                if ($tenantUser) {
                    // Log in via tenant guard
                    \Illuminate\Support\Facades\Auth::guard('tenant')->login($tenantUser, true);
                    request()->session()->regenerate();

                    // Clear token
                    tenancy()->central(function () use ($centralUser) {
                        $centralUser->update([
                            'sso_token' => null,
                            'sso_token_expires_at' => null,
                        ]);
                    });

                    // Log activity
                    \App\Models\Tenant\ActivityLog::create([
                        'description' => 'User logged in via SSO',
                        'causer_id' => $tenantUser->id,
                        'properties' => [
                            'ip' => request()->ip(),
                            'user_agent' => request()->userAgent(),
                        ],
                    ]);

                    // Flash success message
                    session()->flash('success', 'Login berhasil! Selamat datang kembali, ' . $tenantUser->name . '.');

                    return redirect()->intended(route('tenant.dashboard'));
                }
            }
        }

        $this->email = request()->query('email', '');
    }

    protected array $rules = [
        'email' => 'required|email',
        'password' => 'required|string',
    ];

    public function login()
    {
        $this->validate();

        $throttleKey = strtolower($this->email) . '|' . request()->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            throw ValidationException::withMessages([
                'email' => __('Terlalu banyak percobaan masuk. Silakan coba lagi dalam :seconds detik.', ['seconds' => $seconds]),
            ]);
        }

        if (Auth::guard('tenant')->attempt(['email' => $this->email, 'password' => $this->password, 'is_active' => true], $this->remember)) {
            request()->session()->regenerate();
            RateLimiter::clear($throttleKey);

            \App\Models\Tenant\ActivityLog::create([
                'description' => 'User logged in to tenant portal',
                'causer_id' => Auth::guard('tenant')->id(),
                'properties' => [
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ],
            ]);

            // Flash success message
            session()->flash('success', 'Login berhasil! Selamat datang kembali.');

            return redirect()->intended(route('tenant.dashboard'));
        }

        RateLimiter::hit($throttleKey, 60);

        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }

    public function render()
    {
        $scheme = request()->getScheme();
        $port = request()->getPort() ? ':' . request()->getPort() : '';
        $host = request()->getHost();
        $centralDomains = config('tenancy.central_domains', ['127.0.0.1', 'localhost', 'clean.jarvisid.com']);
        
        $centralHost = 'localhost';
        if ($host !== 'localhost' && $host !== '127.0.0.1') {
            foreach ($centralDomains as $domain) {
                if ($domain !== 'localhost' && $domain !== '127.0.0.1') {
                    $centralHost = $domain;
                    break;
                }
            }
        } else {
            $centralHost = $host;
        }
        
        $googleAuthUrl = $scheme . '://' . $centralHost . $port . '/auth/google';
        $centralUrl = $scheme . '://' . $centralHost . $port;

        return <<<'HTML'
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
                <h2 class="mt-6 text-center text-3xl font-extrabold text-[#1A1D23] tracking-tight">Masuk ke Outlet</h2>
                <p class="mt-2 text-center text-sm text-[#8896A6]">Masukkan kredensial staf Anda</p>
            </div>

            <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
                <div class="bg-white py-8 px-4 border border-[#E2E7EF] shadow-xl shadow-[#1E3A5F]/5 sm:rounded-2xl sm:px-10">
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

                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input wire:model="remember" id="remember-me" type="checkbox" class="h-4 w-4 text-[#1E3A5F] focus:ring-[#1E3A5F]/20 border-[#E2E7EF] bg-[#F8F9FC] rounded">
                                <label for="remember-me" class="ml-2 block text-sm text-[#8896A6]">Ingat saya</label>
                            </div>
                            <a href="#" class="text-sm font-medium text-[#1E3A5F] hover:text-[#2A5082] transition-colors">Lupa password?</a>
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
                            <a href="{{ $googleAuthUrl }}" class="w-full flex items-center justify-center gap-3 py-2.5 px-4 border border-[#E2E7EF] rounded-xl shadow-sm text-sm font-semibold text-[#4A5568] bg-[#F8F9FC] hover:bg-slate-50 hover:border-[#1E3A5F]/20 transition-all cursor-pointer">
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
                        <a href="{{ $centralUrl }}" class="inline-flex items-center text-xs font-semibold text-[#8896A6] hover:text-[#1E3A5F] transition-colors">
                            <svg class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            Kembali ke Utama
                        </a>
                    </div>
                </div>
            </div>
        </div>
        HTML;
    }
}
