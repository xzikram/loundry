<?php

namespace Database\Seeders\Tenant;

use Illuminate\Database\Seeder;
use App\Models\Tenant\Setting;
use App\Models\Tenant\ReceiptTemplate;

class DefaultSettingsSeeder extends Seeder
{
    public function run(): void
    {
        // Default settings
        $settings = [
            // General settings
            ['key' => 'laundry_name', 'value' => 'KLIIN Laundry', 'group' => 'general', 'type' => 'string'],
            ['key' => 'currency', 'value' => 'IDR', 'group' => 'general', 'type' => 'string'],
            ['key' => 'currency_symbol', 'value' => 'Rp', 'group' => 'general', 'type' => 'string'],
            
            // Tax settings
            ['key' => 'tax_rate', 'value' => '11', 'group' => 'tax', 'type' => 'integer'], // 11% VAT
            ['key' => 'tax_enabled', 'value' => 'true', 'group' => 'tax', 'type' => 'boolean'],
            
            // POS and Invoicing
            ['key' => 'invoice_prefix', 'value' => 'INV-', 'group' => 'invoice', 'type' => 'string'],
            ['key' => 'invoice_digits', 'value' => '6', 'group' => 'invoice', 'type' => 'integer'],
            
            // WhatsApp Notifications
            ['key' => 'whatsapp_notifications_enabled', 'value' => 'false', 'group' => 'notifications', 'type' => 'boolean'],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }

        // Default receipt template
        ReceiptTemplate::create([
            'name' => 'Standard Thermal Receipt',
            'header' => "KLIIN\nPremium Laundry Management\n--------------------------",
            'footer' => "Thank you for washing with us!\nPlease check your laundry before leaving.\nAll claims must be made within 24 hours.",
            'layout' => [
                'show_logo' => false,
                'show_tax' => true,
                'show_cashier' => true,
                'show_barcode' => true
            ],
            'is_default' => true,
            'is_active' => true
        ]);
    }
}
