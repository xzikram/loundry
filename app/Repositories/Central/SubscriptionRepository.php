<?php

namespace App\Repositories\Central;

use App\Models\Central\Subscription;
use App\Repositories\Contracts\SubscriptionRepositoryInterface;
use Illuminate\Support\Collection;
use App\Enums\SubscriptionStatus;

class SubscriptionRepository implements SubscriptionRepositoryInterface
{
    public function all(): Collection
    {
        return Subscription::with(['tenant', 'plan'])->get();
    }

    public function find(int $id): ?Subscription
    {
        return Subscription::with(['tenant', 'plan'])->find($id);
    }

    public function findActiveByTenant(string $tenantId): ?Subscription
    {
        return Subscription::where('tenant_id', $tenantId)
            ->where('status', SubscriptionStatus::ACTIVE)
            ->where('ends_at', '>', now())
            ->first();
    }

    public function create(array $data): Subscription
    {
        return Subscription::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $subscription = Subscription::find($id);
        if (!$subscription) {
            return false;
        }
        return $subscription->update($data);
    }

    public function expireTrialTenants(): int
    {
        return Subscription::where('status', SubscriptionStatus::TRIAL)
            ->where('trial_ends_at', '<', now())
            ->update(['status' => SubscriptionStatus::EXPIRED]);
    }
}
