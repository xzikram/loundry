<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        // Register dynamic gates for tenant user permissions
        \Illuminate\Support\Facades\Gate::before(function ($user, string $ability) {
            if ($user instanceof \App\Models\Tenant\User) {
                // Tenant Owner bypasses all authorization checks
                if ($user->role && $user->role->slug === 'owner') {
                    return true;
                }
                
                // Otherwise, check if user's role has the matching permission slug
                return $user->role && $user->role->permissions()
                    ->where('slug', $ability)
                    ->exists();
            }
            return null;
        });
    }
}
