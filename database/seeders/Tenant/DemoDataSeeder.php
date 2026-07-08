<?php

namespace Database\Seeders\Tenant;

use Illuminate\Database\Seeder;
use App\Models\Tenant\ServiceCategory;
use App\Models\Tenant\Customer;
use App\Models\Tenant\Outlet;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Default Categories
        $categories = [
            [
                'name' => 'Cuci Lipat (Wash & Fold)',
                'slug' => 'wash-fold',
                'icon' => 'sparkles',
                'description' => 'Layanan cuci bersih dan lipat rapi',
                'sort_order' => 1,
                'is_active' => true
            ],
            [
                'name' => 'Cuci Setrika (Wash & Iron)',
                'slug' => 'wash-iron',
                'icon' => 'fire',
                'description' => 'Layanan cuci bersih, setrika wangi, lipat/gantang rapi',
                'sort_order' => 2,
                'is_active' => true
            ],
            [
                'name' => 'Dry Cleaning / Satuan',
                'slug' => 'dry-cleaning',
                'icon' => 'shirt',
                'description' => 'Layanan cuci kering satuan untuk pakaian khusus',
                'sort_order' => 3,
                'is_active' => true
            ]
        ];

        $catModels = [];
        foreach ($categories as $cat) {
            $catModels[$cat['slug']] = ServiceCategory::create($cat);
        }

        // 2. Create Default Services inside Categories
        $services = [
            // Wash & Fold
            [
                'category_slug' => 'wash-fold',
                'name' => 'Cuci Lipat Kiloan',
                'slug' => 'cuci-lipat-kiloan',
                'description' => 'Cuci lipat reguler dihitung per kilogram',
                'unit' => 'kg',
                'estimated_duration_hours' => 48,
                'price_regular' => 6000.00,
                'price_express' => 10000.00,
                'price_super_express' => 15000.00,
            ],
            // Wash & Iron
            [
                'category_slug' => 'wash-iron',
                'name' => 'Cuci Setrika Kiloan',
                'slug' => 'cuci-setrika-kiloan',
                'description' => 'Cuci setrika reguler dihitung per kilogram',
                'unit' => 'kg',
                'estimated_duration_hours' => 48,
                'price_regular' => 8000.00,
                'price_express' => 14000.00,
                'price_super_express' => 20000.00,
            ],
            // Dry Cleaning Satuan
            [
                'category_slug' => 'dry-cleaning',
                'name' => 'Jas / Blazer Satuan',
                'slug' => 'jas-blazer-satuan',
                'description' => 'Layanan dry clean jas pria/wanita per potong',
                'unit' => 'pcs',
                'estimated_duration_hours' => 72,
                'price_regular' => 25000.00,
                'price_express' => 40000.00,
                'price_super_express' => 60000.00,
            ],
            [
                'category_slug' => 'dry-cleaning',
                'name' => 'Bed Cover Satuan',
                'slug' => 'bed-cover-satuan',
                'description' => 'Layanan cuci bed cover per potong',
                'unit' => 'pcs',
                'estimated_duration_hours' => 72,
                'price_regular' => 30000.00,
                'price_express' => 50000.00,
                'price_super_express' => 75000.00,
            ]
        ];

        $outlet = Outlet::first();

        foreach ($services as $srvData) {
            $catSlug = $srvData['category_slug'];
            unset($srvData['category_slug']);

            $prices = [
                'regular' => $srvData['price_regular'],
                'express' => $srvData['price_express'],
                'super_express' => $srvData['price_super_express'],
            ];
            unset($srvData['price_regular'], $srvData['price_express'], $srvData['price_super_express']);

            $cat = $catModels[$catSlug];
            $service = $cat->services()->create($srvData);

            // Create prices mapping
            foreach ($prices as $type => $amount) {
                $service->prices()->create([
                    'outlet_id' => $outlet->id,
                    'price_type' => $type,
                    'price' => $amount,
                    'min_weight' => $type === 'regular' ? 1.00 : 0.50
                ]);
            }
        }

        // 3. Create Default Customers
        $customers = [
            [
                'name' => 'Budi Santoso',
                'email' => 'budi@gmail.com',
                'phone' => '081234567890',
                'address' => 'Jl. Merdeka No. 10, Jakarta',
                'gender' => 'male',
                'membership_code' => 'MEMB-001',
                'loyalty_points' => 10,
            ],
            [
                'name' => 'Susi Susanti',
                'email' => 'susi@gmail.com',
                'phone' => '082345678901',
                'address' => 'Jl. Mawar No. 4, Jakarta',
                'gender' => 'female',
                'membership_code' => 'MEMB-002',
                'loyalty_points' => 25,
            ]
        ];

        foreach ($customers as $cust) {
            Customer::create($cust);
        }
    }
}
