<?php

namespace App\Repositories\Tenant;

use App\Models\Tenant\Order;
use App\Models\Tenant\OrderItem;
use App\Models\Tenant\OrderStatusModel;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Enums\OrderStatus;

class OrderRepository implements OrderRepositoryInterface
{
    public function all(): Collection
    {
        return Order::with(['customer', 'outlet', 'creator'])->latest()->get();
    }

    public function find(int $id): ?Order
    {
        return Order::with(['customer', 'outlet', 'creator', 'items.service', 'payments', 'statuses.user'])->find($id);
    }

    public function findByInvoice(string $invoiceNumber): ?Order
    {
        return Order::where('invoice_number', $invoiceNumber)->first();
    }

    public function create(array $data, array $items): Order
    {
        return DB::transaction(function () use ($data, $items) {
            $order = Order::create($data);

            foreach ($items as $item) {
                $order->items()->create($item);
            }

            // Create initial status log
            $order->statuses()->create([
                'user_id' => $data['created_by'],
                'status' => OrderStatus::PENDING,
                'notes' => 'Order created.',
            ]);

            return $order;
        });
    }

    public function updateStatus(int $id, string $status, ?string $notes = null, ?int $userId = null): bool
    {
        return DB::transaction(function () use ($id, $status, $notes, $userId) {
            $order = Order::find($id);
            if (!$order) {
                return false;
            }

            $order->status = $status;
            
            if ($status === OrderStatus::COMPLETED->value) {
                $order->completed_at = now();
            } elseif ($status === OrderStatus::PICKED_UP->value) {
                $order->picked_up_at = now();
            } elseif ($status === OrderStatus::DELIVERED->value) {
                $order->delivered_at = now();
            }

            $order->save();

            // Log status change
            $order->statuses()->create([
                'user_id' => $userId,
                'status' => $status,
                'notes' => $notes ?? "Status updated to " . $status,
            ]);

            return true;
        });
    }

    public function getTodayStats(): array
    {
        $today = now()->startOfDay();

        // Single query to fetch total count, completed count and total revenue of today's orders
        $totals = Order::where('created_at', '>=', $today)
            ->selectRaw("
                COUNT(*) as total_orders, 
                SUM(CASE WHEN payment_status = 'paid' THEN total ELSE 0 END) as total_revenue,
                SUM(CASE WHEN status = '" . OrderStatus::COMPLETED->value . "' THEN 1 ELSE 0 END) as total_completed
            ")
            ->first();

        // Query count of all currently processing orders (regardless of creation date)
        $processingCount = Order::whereIn('status', [
            OrderStatus::PROCESSING, OrderStatus::WASHING, OrderStatus::DRYING, OrderStatus::IRONING, OrderStatus::PACKING
        ])->count();

        return [
            'revenue' => (float) ($totals->total_revenue ?? 0.00),
            'orders' => (int) ($totals->total_orders ?? 0),
            'processing' => $processingCount,
            'completed' => (int) ($totals->total_completed ?? 0),
        ];
    }

    public function getRevenueChartData(string $period): array
    {
        $startDate = now()->subDays(6)->startOfDay();
        
        // Single query grouping revenue by date to prevent N+1 query loop
        $rawTotals = Order::where('created_at', '>=', $startDate)
            ->where('payment_status', 'paid')
            ->selectRaw('DATE(created_at) as date_only, SUM(total) as total')
            ->groupBy('date_only')
            ->pluck('total', 'date_only')
            ->toArray();

        $days = collect(range(6, 0))->map(fn($day) => now()->subDays($day)->format('Y-m-d'));
        
        $values = $days->map(function ($date) use ($rawTotals) {
            return (float) ($rawTotals[$date] ?? 0.00);
        })->toArray();

        return [
            'labels' => $days->map(fn($date) => date('D', strtotime($date)))->toArray(),
            'values' => $values
        ];
    }
}
