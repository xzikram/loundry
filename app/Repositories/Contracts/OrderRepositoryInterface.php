<?php

namespace App\Repositories\Contracts;

use App\Models\Tenant\Order;
use Illuminate\Support\Collection;

interface OrderRepositoryInterface
{
    public function all(): Collection;
    public function find(int $id): ?Order;
    public function findByInvoice(string $invoiceNumber): ?Order;
    public function create(array $data, array $items): Order;
    public function updateStatus(int $id, string $status, ?string $notes = null, ?int $userId = null): bool;
    public function getTodayStats(): array;
    public function getRevenueChartData(string $period): array;
}
