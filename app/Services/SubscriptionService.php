<?php

namespace App\Services;

use App\Repositories\Contracts\SubscriptionRepositoryInterface;
use App\Repositories\Contracts\TenantRepositoryInterface;
use App\Repositories\Contracts\PlanRepositoryInterface;
use App\DTOs\SubscriptionData;
use App\Models\Central\Subscription;
use App\Enums\SubscriptionStatus;
use App\Enums\TenantStatus;
use App\Models\Central\Tenant;

class SubscriptionService
{
    public function __construct(
        protected SubscriptionRepositoryInterface $subscriptionRepo,
        protected TenantRepositoryInterface $tenantRepo,
        protected PlanRepositoryInterface $planRepo
    ) {}

    public function createTrialSubscription(string $tenantId, int $planId): Subscription
    {
        $plan = $this->planRepo->find($planId);
        $trialDays = $plan ? $plan->trial_days : 14;

        $data = SubscriptionData::fromArray([
            'tenant_id' => $tenantId,
            'plan_id' => $planId,
            'status' => SubscriptionStatus::TRIAL->value,
            'starts_at' => now()->toDateTimeString(),
            'ends_at' => now()->addDays($trialDays)->toDateTimeString(),
            'trial_ends_at' => now()->addDays($trialDays)->toDateTimeString(),
            'amount' => 0.00,
            'auto_renew' => true,
        ]);

        return $this->subscriptionRepo->create($data->toArray());
    }

    public function activateSubscription(string $tenantId, int $planId, float $amount, string $paymentMethod): Subscription
    {
        $startsAt = now();
        $endsAt = now()->addMonth(); // Standard monthly duration

        $data = SubscriptionData::fromArray([
            'tenant_id' => $tenantId,
            'plan_id' => $planId,
            'status' => SubscriptionStatus::ACTIVE->value,
            'starts_at' => $startsAt->toDateTimeString(),
            'ends_at' => $endsAt->toDateTimeString(),
            'amount' => $amount,
            'payment_method' => $paymentMethod,
            'auto_renew' => true,
        ]);

        $subscription = $this->subscriptionRepo->create($data->toArray());

        // Update tenant status to active
        $this->tenantRepo->updateStatus($tenantId, TenantStatus::ACTIVE->value);

        return $subscription;
    }

    public function checkFeatureAccess(string $tenantId, string $featureKey): bool
    {
        $tenant = $this->tenantRepo->find($tenantId);
        if (!$tenant || $tenant->status === TenantStatus::SUSPENDED) {
            return false;
        }

        // Get active subscription
        $subscription = $tenant->activeSubscription ?? $tenant->subscriptions()
            ->where('status', SubscriptionStatus::TRIAL)
            ->where('trial_ends_at', '>', now())
            ->first();

        if (!$subscription) {
            return false;
        }

        // Check if plan has the feature key
        $plan = $subscription->plan;
        if (!$plan) {
            return false;
        }

        return $plan->features()->where('key', $featureKey)->where('is_active', true)->exists();
    }

    public function checkLimit(string $tenantId, string $limitField, int $currentCount): bool
    {
        $tenant = $this->tenantRepo->find($tenantId);
        if (!$tenant) {
            return false;
        }

        $subscription = $tenant->activeSubscription ?? $tenant->subscriptions()
            ->where('status', SubscriptionStatus::TRIAL)
            ->where('trial_ends_at', '>', now())
            ->first();

        if (!$subscription) {
            return false;
        }

        $plan = $subscription->plan;
        if (!$plan) {
            return false;
        }

        $limit = match ($limitField) {
            'users' => $plan->max_users,
            'outlets' => $plan->max_outlets,
            'storage' => $plan->max_storage_gb,
            default => 0,
        };

        return $currentCount < $limit;
    }
}
