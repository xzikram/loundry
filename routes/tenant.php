<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

// Livewire Pages
use App\Livewire\Tenant\Auth\LoginPage;
use App\Livewire\Tenant\Dashboard\DashboardPage;
use App\Livewire\Tenant\Pos\PosBillingPage;
use App\Livewire\Tenant\Orders\OrderListPage;
use App\Livewire\Tenant\Orders\OrderDetailPage;
use App\Livewire\Tenant\Orders\PrintPreviewPage;
use App\Livewire\Tenant\Customers\CustomerListPage;
use App\Livewire\Tenant\Inventory\InventoryPage;
use App\Livewire\Tenant\Expenses\ExpensePage;
use App\Livewire\Tenant\Staff\StaffPage;
use App\Livewire\Tenant\Reports\ReportsPage;
use App\Livewire\Tenant\Settings\SettingsPage;

// Controllers
use App\Http\Controllers\Tenant\TrackingController;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
*/

$host = request()->getHost();
$centralDomains = config('tenancy.central_domains', ['127.0.0.1', 'localhost', 'laundrypromax.test']);
$isCentral = in_array($host, $centralDomains);

if (app()->runningInConsole() || !$isCentral) {
    Route::middleware([
        'web',
        InitializeTenancyByDomain::class,
        PreventAccessFromCentralDomains::class,
    ])->group(function () {

        // Public Tenant Landing Page
        Route::get('/', \App\Livewire\Tenant\Auth\TenantLandingPage::class)->name('tenant.landing');

        // Public Tracking Routes
        Route::get('/track', [TrackingController::class, 'index'])->name('tenant.track.index');
        Route::get('/track/{invoice_number}', [TrackingController::class, 'show'])->name('tenant.track');

        Route::middleware('guest:tenant')->group(function () {
            Route::get('/login', LoginPage::class)->name('login');
        });

        // Authenticated Tenant Portal routes
        Route::middleware([
            'auth:tenant',
            \App\Http\Middleware\EnsureSubscriptionValid::class
        ])->group(function () {

            // Core
            Route::get('/dashboard', DashboardPage::class)->name('tenant.dashboard');
            Route::get('/pos', PosBillingPage::class)->name('tenant.pos');
            Route::get('/orders', OrderListPage::class)->name('tenant.orders');
            Route::get('/orders/{id}', OrderDetailPage::class)->name('tenant.order-details');
            Route::get('/orders/{id}/print', PrintPreviewPage::class)->name('tenant.orders.print');

            // Management
            Route::get('/customers', CustomerListPage::class)->name('tenant.customers');
            Route::get('/inventory', InventoryPage::class)->name('tenant.inventory');
            Route::get('/expenses', ExpensePage::class)->name('tenant.expenses');
            Route::get('/staff', StaffPage::class)->name('tenant.staff');

            // Insight
            Route::get('/reports', ReportsPage::class)->name('tenant.reports');
            Route::get('/settings', SettingsPage::class)->name('tenant.settings');

            // Website Builder Module
            Route::get('/website', \App\Livewire\Tenant\Website\WebsiteDashboard::class)->name('tenant.website.dashboard');
            Route::get('/website/pages', \App\Livewire\Tenant\Website\PageList::class)->name('tenant.website.pages');
            Route::get('/website/builder/{id}', \App\Livewire\Tenant\Website\PageBuilder::class)->name('tenant.website.builder');
            Route::get('/website/media', \App\Livewire\Tenant\Website\MediaLibraryPage::class)->name('tenant.website.media');
            Route::get('/website/popups', \App\Livewire\Tenant\Website\PopupCampaignsPage::class)->name('tenant.website.popups');
            Route::get('/website/navigation', \App\Livewire\Tenant\Website\NavigationBuilderPage::class)->name('tenant.website.navigation');
            Route::get('/website/theme', \App\Livewire\Tenant\Website\ThemeSettingsPage::class)->name('tenant.website.theme');

            // Logout
            Route::post('/logout', function () {
                Auth::guard('tenant')->logout();
                request()->session()->invalidate();
                request()->session()->regenerateToken();
                return redirect()->route('login');
            })->name('tenant.logout');
        });
    });
}
