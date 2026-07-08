<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Central\Payment;
use App\Enums\PaymentStatus;
use Illuminate\Support\Facades\DB;

class RevenueChart extends ChartWidget
{
    protected ?string $heading = 'Subscription Revenue (MRR)';
    
    protected string $color = 'success';

    protected function getData(): array
    {
        // Get paid transaction values over the past 6 months
        $months = collect(range(5, 0))->map(function ($i) {
            return now()->subMonths($i)->format('Y-m');
        });

        $values = $months->map(function ($yearMonth) {
            $start = now()->createFromFormat('Y-m', $yearMonth)->startOfMonth();
            $end = now()->createFromFormat('Y-m', $yearMonth)->endOfMonth();

            return (float) Payment::where('status', PaymentStatus::PAID)
                ->whereBetween('paid_at', [$start, $end])
                ->sum('total');
        });

        $labels = $months->map(function ($yearMonth) {
            return now()->createFromFormat('Y-m', $yearMonth)->format('F Y');
        });

        return [
            'datasets' => [
                [
                    'label' => 'Revenue (IDR)',
                    'data' => $values->toArray(),
                    'fill' => 'start',
                ],
            ],
            'labels' => $labels->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
