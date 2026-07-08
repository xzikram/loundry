<?php

namespace App\Actions\Subscription;

use App\DTOs\SubscriptionData;
use App\Models\Central\Subscription;
use App\Models\Central\Tenant;
use App\Enums\TenantStatus;
use App\Enums\SubscriptionStatus;
use Illuminate\Support\Facades\DB;

class CreateSubscriptionAction
{
    public function execute(SubscriptionData $data): Subscription
    {
        return DB::transaction(function () use ($data) {
            // Cancel any current active subscriptions
            Subscription::where('tenant_id', $data->tenantId)
                ->where('status', SubscriptionStatus::ACTIVE)
                ->update(['status' => SubscriptionStatus::CANCELLED, 'cancelled_at' => now()]);

            // Create new subscription
            $subscription = Subscription::create($data->toArray());

            // Update Tenant status to active
            $tenant = Tenant::find($data->tenantId);
            if ($tenant && $data->status === SubscriptionStatus::ACTIVE->value) {
                $tenant->status = TenantStatus::ACTIVE;
                $tenant->save();
            }

            return $subscription;
        });
    }
}
