<?php

namespace App\Actions\Tenant;

use App\Models\Central\Tenant;

class DeleteTenantAction
{
    public function execute(string $tenantId): bool
    {
        $tenant = Tenant::find($tenantId);
        if (!$tenant) {
            return false;
        }

        return (bool) $tenant->delete();
    }
}
