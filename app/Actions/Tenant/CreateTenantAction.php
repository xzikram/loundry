<?php

namespace App\Actions\Tenant;

use App\DTOs\TenantData;
use App\Models\Central\Tenant;
use Illuminate\Support\Facades\DB;

class CreateTenantAction
{
    public function execute(TenantData $data, string $domain): Tenant
    {
        // Create tenant
        $tenant = Tenant::create($data->toArray());

        // Create domain (e.g. abc.laundrypromax.test)
        $tenant->domains()->create([
            'domain' => $domain
        ]);

        return $tenant;
    }
}
