<?php

namespace App\Actions\Tenant;

use App\Models\Central\Tenant;
use App\Enums\TenantStatus;

class ActivateTenantAction
{
    public function execute(string $tenantId): bool
    {
        $tenant = Tenant::find($tenantId);
        if (!$tenant) {
            return false;
        }

        $tenant->status = TenantStatus::ACTIVE;
        return $tenant->save();
    }
}
