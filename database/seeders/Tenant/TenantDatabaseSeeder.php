<?php

namespace Database\Seeders\Tenant;

use Illuminate\Database\Seeder;
use App\Models\Tenant\Outlet;
use App\Models\Tenant\User;
use App\Models\Tenant\Role;

class TenantDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Roles & Permissions
        $this->call(RolePermissionSeeder::class);

        // 2. Seed Default Settings & Templates
        $this->call(DefaultSettingsSeeder::class);

        // 3. Create Default Primary Outlet
        $tenant = tenant();
        $outlet = Outlet::create([
            'name' => $tenant->name . ' - Main Branch',
            'code' => 'OUT-MAIN',
            'address' => 'Primary Tenant Address',
            'phone' => $tenant->phone ?? '021-123456',
            'email' => $tenant->email,
            'is_main' => true,
            'is_active' => true,
            'operating_hours' => [
                'monday' => ['start' => '08:00', 'end' => '20:00'],
                'tuesday' => ['start' => '08:00', 'end' => '20:00'],
                'wednesday' => ['start' => '08:00', 'end' => '20:00'],
                'thursday' => ['start' => '08:00', 'end' => '20:00'],
                'friday' => ['start' => '08:00', 'end' => '20:00'],
                'saturday' => ['start' => '08:00', 'end' => '20:00'],
                'sunday' => ['start' => '08:00', 'end' => '20:00'],
            ]
        ]);

        // 4. Create Tenant Owner Account
        $ownerRole = Role::where('slug', 'owner')->first();

        // Retrieve owner info from tenant config data
        $ownerName = $tenant->owner_name ?? $tenant->getAttribute('owner_name') ?? 'Owner';
        $ownerEmail = $tenant->owner_email ?? $tenant->getAttribute('owner_email') ?? $tenant->email;
        $ownerPassword = $tenant->owner_password ?? $tenant->getAttribute('owner_password') ?? bcrypt('laundrypromax123');
        $ownerPhone = $tenant->owner_phone ?? $tenant->getAttribute('owner_phone') ?? $tenant->phone;

        User::create([
            'name' => $ownerName,
            'email' => $ownerEmail,
            'phone' => $ownerPhone,
            'password' => $ownerPassword,
            'role_id' => $ownerRole?->id,
            'outlet_id' => $outlet->id,
            'is_active' => true
        ]);

        // 5. Seed Demo Service & Customers
        $this->call(DemoDataSeeder::class);
    }
}
