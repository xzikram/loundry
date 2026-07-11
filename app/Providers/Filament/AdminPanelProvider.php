<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\View\PanelsRenderHook;
use Filament\Support\Facades\FilamentView;
use Illuminate\Support\HtmlString;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandName('Spinly Admin')
            ->font('Outfit')
            ->brandLogo(fn () => view('filament.brand-logo'))
            ->favicon('https://img.icons8.com/color/192/000000/washing-machine.png')
            ->sidebarCollapsibleOnDesktop()
            ->colors([
                'primary' => [
                    50 => '#FDF8EB',
                    100 => '#FAF0D4',
                    200 => '#F5E1A9',
                    300 => '#E8C97A',
                    400 => '#D4A853',
                    500 => '#B8913A',
                    600 => '#9A7A30',
                    700 => '#7C6327',
                    800 => '#5E4C1E',
                    900 => '#403514',
                    950 => '#201A0A',
                ],
                'warning' => [
                    50 => '#FDF8EB',
                    100 => '#FAF0D4',
                    200 => '#F5E1A9',
                    300 => '#E8C97A',
                    400 => '#D4A853',
                    500 => '#B8913A',
                    600 => '#9A7A30',
                    700 => '#7C6327',
                    800 => '#5E4C1E',
                    900 => '#403514',
                    950 => '#201A0A',
                ],
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }

    public function boot(): void
    {
        FilamentView::registerRenderHook(
            PanelsRenderHook::HEAD_END,
            fn (): HtmlString => new HtmlString('
                <style>
                    /* Custom premium styling for Spinly Filament Admin */
                    .fi-sidebar {
                        background: linear-gradient(180deg, #0F1A2E 0%, #131d31 50%, #17263e 100%) !important;
                        border-right: 1px solid rgba(212, 168, 83, 0.15) !important;
                    }
                    .fi-sidebar-item-active {
                        background: linear-gradient(90deg, rgba(212, 168, 83, 0.12) 0%, rgba(16, 185, 129, 0.04) 100%) !important;
                        border: 1px solid rgba(212, 168, 83, 0.2) !important;
                        border-radius: 12px !important;
                    }
                    .fi-ta-content, .fi-section, .fi-card {
                        background-color: #111827 !important;
                        border: 1px solid rgba(255, 255, 255, 0.05) !important;
                        border-radius: 16px !important;
                    }
                    .fi-ta-header-cell {
                        background-color: #161e2f !important;
                    }
                    .fi-btn-color-primary {
                        background: linear-gradient(135deg, #D4A853 0%, #B8913A 100%) !important;
                        box-shadow: 0 4px 14px 0 rgba(212, 168, 83, 0.25) !important;
                        border-radius: 12px !important;
                        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
                        border: none !important;
                    }
                    .fi-btn-color-primary:hover {
                        transform: translateY(-1px) !important;
                        box-shadow: 0 6px 20px 0 rgba(212, 168, 83, 0.35) !important;
                    }
                    /* Smooth font rendering */
                    body {
                        -webkit-font-smoothing: antialiased;
                        -moz-osx-font-smoothing: grayscale;
                    }
                </style>
            ')
        );
    }
}
