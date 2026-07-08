<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Central\Tenant;
use App\Enums\TenantStatus;
use App\Models\Central\Payment;
use App\Enums\PaymentStatus;

class TenantStatsOverview extends BaseWidget
{
    protected ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        $totalTenants = Tenant::count();
        $activeTenants = Tenant::where('status', TenantStatus::ACTIVE)->count();
        $trialTenants = Tenant::where('status', TenantStatus::TRIAL)->count();
        
        // Calculate MRR (Monthly Recurring Revenue) representation
        $mrr = (float) Payment::where('status', PaymentStatus::PAID)
            ->where('paid_at', '>=', now()->startOfMonth())
            ->sum('total');

        return [
            Stat::make('Total Tenants', $totalTenants)
                ->description('All registered laundry businesses')
                ->descriptionIcon('heroicon-m-building-storefront')
                ->color('primary'),
            Stat::make('Active Subscribers', $activeTenants)
                ->description('Active paid accounts')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),
            Stat::make('Trial Accounts', $trialTenants)
                ->description('Tenants in trial period')
                ->descriptionIcon('heroicon-m-clock')
                ->color('info'),
            Stat::make('MRR (This Month)', 'Rp ' . number_format($mrr, 0, ',', '.'))
                ->description('Monthly Recurring Revenue')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
        ];
    }
}
