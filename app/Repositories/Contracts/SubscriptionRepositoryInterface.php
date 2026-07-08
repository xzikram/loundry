<?php

namespace App\Repositories\Contracts;

use App\Models\Central\Subscription;
use Illuminate\Support\Collection;

interface SubscriptionRepositoryInterface
{
    public function all(): Collection;
    public function find(int $id): ?Subscription;
    public function findActiveByTenant(string $tenantId): ?Subscription;
    public function create(array $data): Subscription;
    public function update(int $id, array $data): bool;
    public function expireTrialTenants(): int;
}
