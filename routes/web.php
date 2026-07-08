<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Central\Auth\CentralLoginPage;

Route::get('/', function () {
    return view('welcome');
});

// Unified login route
Route::get('/login', CentralLoginPage::class)->name('login');

// Unified register route
Route::get('/register', \App\Livewire\Central\Auth\RegisterPage::class)->name('register');

// Redirect default Filament admin login to unified central login page
Route::get('/admin/login', function () {
    return redirect()->route('login');
})->name('filament.admin.auth.login');

// Dedicated Chooser Route (Plain HTML/GET to prevent Livewire page expired exceptions)
Route::middleware('auth')->get('/login/chooser', function () {
    $user = auth()->user();
    
    // Find associated tenant
    $tenant = \App\Models\Central\Tenant::where('email', $user->email)->first();
    $subdomain = $tenant ? $tenant->id : '';

    return view('auth.chooser', [
        'user' => $user,
        'subdomain' => $subdomain
    ]);
})->name('login.chooser');
