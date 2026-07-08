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

        return [
            'revenue' => (float) Order::where('created_at', '>=', $today)
                ->where('payment_status', 'paid')
                ->sum('total'),
            'orders' => Order::where('created_at', '>=', $today)->count(),
            'processing' => Order::whereIn('status', [
                OrderStatus::PROCESSING, OrderStatus::WASHING, OrderStatus::DRYING, OrderStatus::IRONING, OrderStatus::PACKING
            ])->count(),
            'completed' => Order::where('status', OrderStatus::COMPLETED)
                ->where('created_at', '>=', $today)
                ->count(),
        ];
    }

    public function getRevenueChartData(string $period): array
    {
        // Sample static return representation, usually DB query group by date
        $days = collect(range(6, 0))->map(fn($day) => now()->subDays($day)->format('Y-m-d'));
        
        $data = $days->map(function ($date) {
            return [
                'date' => $date,
                'total' => (float) Order::whereDate('created_at', $date)
                    ->where('payment_status', 'paid')
                    ->sum('total')
            ];
        });

        return [
            'labels' => $days->map(fn($date) => date('D', strtotime($date)))->toArray(),
            'values' => $data->pluck('total')->toArray()
        ];
    }
}
