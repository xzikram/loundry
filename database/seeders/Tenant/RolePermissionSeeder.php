<?php

namespace Database\Seeders\Tenant;

use Illuminate\Database\Seeder;
use App\Models\Tenant\Role;
use App\Models\Tenant\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Define default permissions
        $permissions = [
            // Dashboard
            ['name' => 'View Dashboard', 'slug' => 'view-dashboard', 'group' => 'dashboard', 'description' => 'Allow viewing dashboard analytics'],
            
            // Orders & POS
            ['name' => 'Create Order', 'slug' => 'create-order', 'group' => 'orders', 'description' => 'Allow creating POS laundry orders'],
            ['name' => 'View Orders', 'slug' => 'view-orders', 'group' => 'orders', 'description' => 'Allow viewing laundry order lists'],
            ['name' => 'Process Order Status', 'slug' => 'process-order-status', 'group' => 'orders', 'description' => 'Allow progressing order tracking status (drying, ironing etc)'],
            ['name' => 'Process Order Payment', 'slug' => 'process-order-payment', 'group' => 'orders', 'description' => 'Allow recording order payments'],
            ['name' => 'Cancel Order', 'slug' => 'cancel-order', 'group' => 'orders', 'description' => 'Allow cancelling laundry orders'],
            
            // Customer Management
            ['name' => 'Manage Customers', 'slug' => 'manage-customers', 'group' => 'customers', 'description' => 'Allow managing customer data'],
            
            // Inventory
            ['name' => 'View Inventory', 'slug' => 'view-inventory', 'group' => 'inventory', 'description' => 'Allow viewing laundry supply stock'],
            ['name' => 'Manage Inventory', 'slug' => 'manage-inventory', 'group' => 'inventory', 'description' => 'Allow adjusting stock, adding inventory'],
            
            // Expenses
            ['name' => 'Manage Expenses', 'slug' => 'manage-expenses', 'group' => 'expenses', 'description' => 'Allow recording cash outflows/expenses'],
            
            // Staff & Attendance
            ['name' => 'Manage Staff', 'slug' => 'manage-staff', 'group' => 'staff', 'description' => 'Allow creating employee accounts'],
            ['name' => 'Manage Attendance', 'slug' => 'manage-attendance', 'group' => 'staff', 'description' => 'Allow reviewing attendance log details'],
            
            // Outlet Settings
            ['name' => 'Manage Outlet Settings', 'slug' => 'manage-outlet-settings', 'group' => 'settings', 'description' => 'Allow configuring receipt templates, printers, taxes'],
        ];

        $permissionModels = [];
        foreach ($permissions as $perm) {
            $permissionModels[$perm['slug']] = Permission::create($perm);
        }

        // Define default roles
        $roles = [
            [
                'name' => 'Owner',
                'slug' => 'owner',
                'description' => 'Tenant Owner with full access',
                'is_system' => true,
                'permissions' => array_keys($permissionModels) // all permissions
            ],
            [
                'name' => 'Manager',
                'slug' => 'manager',
                'description' => 'Outlet Manager with operations access',
                'is_system' => true,
                'permissions' => [
                    'view-dashboard', 'create-order', 'view-orders', 'process-order-status', 
                    'process-order-payment', 'cancel-order', 'manage-customers', 'view-inventory', 
                    'manage-inventory', 'manage-expenses', 'manage-attendance'
                ]
            ],
            [
                'name' => 'Cashier',
                'slug' => 'cashier',
                'description' => 'POS Cashier staff',
                'is_system' => true,
                'permissions' => [
                    'create-order', 'view-orders', 'process-order-status', 'process-order-payment', 
                    'manage-customers', 'view-inventory'
                ]
            ],
            [
                'name' => 'Staff',
                'slug' => 'staff',
                'description' => 'Laundry washer/ironer/driver',
                'is_system' => true,
                'permissions' => [
                    'view-orders', 'process-order-status'
                ]
            ]
        ];

        foreach ($roles as $roleData) {
            $perms = $roleData['permissions'];
            unset($roleData['permissions']);

            $role = Role::create($roleData);
            
            foreach ($perms as $pSlug) {
                if (isset($permissionModels[$pSlug])) {
                    $role->permissions()->attach($permissionModels[$pSlug]->id);
                }
            }
        }
    }
}
