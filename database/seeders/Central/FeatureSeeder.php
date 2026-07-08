<?php

namespace Database\Seeders\Central;

use Illuminate\Database\Seeder;
use App\Models\Central\Feature;

class FeatureSeeder extends Seeder
{
    public function run(): void
    {
        $features = [
            ['name' => 'Multi-Outlet Support', 'key' => 'multi_outlet', 'group' => 'outlets', 'description' => 'Support for managing multiple outlet branches'],
            ['name' => 'Printer Integration', 'key' => 'printer_integration', 'group' => 'pos', 'description' => 'Direct thermal receipt printer connection configurations'],
            ['name' => 'WhatsApp Gateway Integration', 'key' => 'whatsapp_gateway', 'group' => 'notifications', 'description' => 'Automated notifications via WhatsApp to customers'],
            ['name' => 'Custom Receipt Customization', 'key' => 'custom_receipt', 'group' => 'pos', 'description' => 'Full header and footer styling customization for receipts'],
            ['name' => 'Advanced Reports & Analytics', 'key' => 'advanced_reports', 'group' => 'reports', 'description' => 'Dynamic chart dashboards and Excel/PDF exportable reporting modules'],
            ['name' => 'Staff Management & Shifts', 'key' => 'staff_management', 'group' => 'staff', 'description' => 'Shift scheduling and attendance tracking with geolocation checks'],
            ['name' => 'Mobile PWA Access', 'key' => 'pwa_access', 'group' => 'mobile', 'description' => 'Special mobile PWA dashboard and floating POS utilities'],
        ];

        foreach ($features as $feature) {
            Feature::create($feature);
        }
    }
}
