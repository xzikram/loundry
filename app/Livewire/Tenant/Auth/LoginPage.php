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

            return redirect()->intended(route('tenant.dashboard'));
        }

        RateLimiter::hit($throttleKey, 60);

        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }

    public function render()
    {
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
                </div>
            </div>
        </div>
        HTML;
    }
}
