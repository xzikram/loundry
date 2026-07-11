<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Central\Auth\CentralLoginPage;
use App\Http\Controllers\Central\Auth\GoogleAuthController;

Route::get('/', function () {
    return view('welcome');
});

// Unified login route
Route::get('/login', CentralLoginPage::class)->name('login');

// Unified register route
Route::get('/register', \App\Livewire\Central\Auth\RegisterPage::class)->name('register');

// Google OAuth routes
Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
Route::get('/register/complete', [GoogleAuthController::class, 'showCompleteRegistrationForm'])->name('register.complete');
Route::post('/register/complete', [GoogleAuthController::class, 'completeRegistration'])->name('register.complete.submit');

// Redirect default Filament admin login to unified central login page
Route::get('/admin/login', function () {
    return redirect()->route('login');
})->name('filament.admin.auth.login');

// Dedicated Chooser Route (Plain HTML/GET to prevent Livewire page expired exceptions)
Route::middleware('auth')->get('/login/chooser', function () {
    $user = auth()->user();
    
    // Generate secure temporary SSO token
    $ssoToken = \Illuminate\Support\Str::random(60);
    $user->update([
        'sso_token' => $ssoToken,
        'sso_token_expires_at' => now()->addMinutes(5),
    ]);
    
    // Find associated tenant
    $tenant = \App\Models\Central\Tenant::where('email', $user->email)->first();
    $subdomain = $tenant ? $tenant->id : '';

    return view('auth.chooser', [
        'user' => $user,
        'subdomain' => $subdomain,
        'ssoToken' => $ssoToken
    ]);
})->name('login.chooser');
