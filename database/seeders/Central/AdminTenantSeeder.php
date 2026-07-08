<?php

namespace Database\Seeders\Central;

use Illuminate\Database\Seeder;
use App\Actions\Tenant\CreateTenantAction;
use App\DTOs\TenantData;
use App\Models\Central\Tenant;

class AdminTenantSeeder extends Seeder
{
    public function run(): void
    {
        // Check if admin tenant already exists
        if (Tenant::where('id', 'admin')->exists()) {
            return;
        }

        $tenantData = new TenantData(
            id: 'admin',
            name: 'Ikram Laundry',
            slug: 'admin',
            email: 'ikramdinata@gmail.com',
            phone: '08123456789',
            status: 'active',
            trialEndsAt: null
        );

        $tenantDataArray = $tenantData->toArray();
        
        // Add data for tenant seeder to create owner with correct password
        $tenantDataArray['data'] = [
            'owner_name' => 'Ikram Dinata',
            'owner_email' => 'ikramdinata@gmail.com',
            'owner_phone' => '08123456789',
            'owner_password' => bcrypt('Ikr@21983'),
        ];

        // Instantiate action and execute
        $action = app(CreateTenantAction::class);
        
        // Seed first domain
        $tenant = $action->execute(TenantData::fromRequest($tenantDataArray), 'admin.localhost');

        // Seed additional domains for full compatibility with other hosts (127.0.0.1 and laundrypromax.test)
        $tenant->domains()->create(['domain' => 'admin.127.0.0.1']);
        $tenant->domains()->create(['domain' => 'admin.laundrypromax.test']);
    }
}
