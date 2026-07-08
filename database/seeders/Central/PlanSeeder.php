<?php

namespace Database\Seeders\Central;

use Illuminate\Database\Seeder;
use App\Models\Central\Plan;
use App\Models\Central\Feature;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $starter = Plan::create([
            'name' => 'Starter Plan',
            'slug' => 'starter',
            'description' => 'Perfect for single small laundry outlets',
            'price_monthly' => 150000.00,
            'price_yearly' => 1500000.00,
            'max_users' => 2,
            'max_outlets' => 1,
            'max_storage_gb' => 1,
            'trial_days' => 14,
            'is_active' => true,
            'sort_order' => 1
        ]);

        $basic = Plan::create([
            'name' => 'Basic Plan',
            'slug' => 'basic',
            'description' => 'Great for growing laundry shops with staff',
            'price_monthly' => 300000.00,
            'price_yearly' => 3000000.00,
            'max_users' => 5,
            'max_outlets' => 2,
            'max_storage_gb' => 5,
            'trial_days' => 14,
            'is_active' => true,
            'sort_order' => 2
        ]);

        $pro = Plan::create([
            'name' => 'Pro Plan',
            'slug' => 'pro',
            'description' => 'Ideal for multi-outlet laundry operations',
            'price_monthly' => 500000.00,
            'price_yearly' => 5000000.00,
            'max_users' => 15,
            'max_outlets' => 5,
            'max_storage_gb' => 20,
            'trial_days' => 14,
            'is_active' => true,
            'sort_order' => 3
        ]);

        $enterprise = Plan::create([
            'name' => 'Enterprise Plan',
            'slug' => 'enterprise',
            'description' => 'Customized capabilities for large laundry businesses',
            'price_monthly' => 1500000.00,
            'price_yearly' => 15000000.00,
            'max_users' => 999, // unlimited representation
            'max_outlets' => 999,
            'max_storage_gb' => 100,
            'trial_days' => 14,
            'is_active' => true,
            'sort_order' => 4
        ]);

        // Link features
        $featureModels = Feature::all()->keyBy('key');

        // Starter gets receipts and printers
        $starter->features()->attach([
            $featureModels['custom_receipt']->id => ['value' => 'true'],
            $featureModels['printer_integration']->id => ['value' => 'true'],
        ]);

        // Basic gets receipts, printers, staff management, PWA
        $basic->features()->attach([
            $featureModels['custom_receipt']->id => ['value' => 'true'],
            $featureModels['printer_integration']->id => ['value' => 'true'],
            $featureModels['staff_management']->id => ['value' => 'true'],
            $featureModels['pwa_access']->id => ['value' => 'true'],
        ]);

        // Pro gets everything except unlimited outlets (handled by max_outlets field anyway)
        $pro->features()->attach([
            $featureModels['custom_receipt']->id => ['value' => 'true'],
            $featureModels['printer_integration']->id => ['value' => 'true'],
            $featureModels['staff_management']->id => ['value' => 'true'],
            $featureModels['pwa_access']->id => ['value' => 'true'],
            $featureModels['advanced_reports']->id => ['value' => 'true'],
            $featureModels['whatsapp_gateway']->id => ['value' => 'true'],
        ]);

        // Enterprise gets all features
        foreach ($featureModels as $feature) {
            $enterprise->features()->attach($feature->id, ['value' => 'true']);
        }
    }
}
