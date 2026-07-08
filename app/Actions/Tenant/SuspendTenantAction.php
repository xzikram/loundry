<?php

namespace App\Actions\Tenant;

use App\Models\Central\Tenant;
use App\Enums\TenantStatus;

class SuspendTenantAction
{
    public function execute(string $tenantId): bool
    {
        $tenant = Tenant::find($tenantId);
        if (!$tenant) {
            return false;
        }

        $tenant->status = TenantStatus::SUSPENDED;
        return $tenant->save();
    }
}
