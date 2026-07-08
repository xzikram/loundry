<?php

namespace Database\Seeders\Central;

use Illuminate\Database\Seeder;
use App\Models\Central\CentralUser;
use App\Enums\UserRole;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        CentralUser::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@laundrypromax.id',
            'password' => bcrypt('superadmin123'),
            'role' => UserRole::SUPER_ADMIN,
            'is_active' => true
        ]);

        CentralUser::create([
            'name' => 'Ikram Dinata',
            'email' => 'ikramdinata@gmail.com',
            'password' => bcrypt('Ikr@21983'),
            'role' => UserRole::SUPER_ADMIN,
            'is_active' => true
        ]);
    }
}
