<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            Central\SuperAdminSeeder::class,
            Central\FeatureSeeder::class,
            Central\PlanSeeder::class,
            Central\AdminTenantSeeder::class,
        ]);
    }
}
