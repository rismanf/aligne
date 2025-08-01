<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PackageCategory;

class PackageCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'signature',
                'display_name' => 'SIGNATURE CLASS PACK',
                'description' => 'Premium signature class packages with various class combinations',
                'sort_order' => 1,
                'is_active' => true
            ],
            [
                'name' => 'functional',
                'display_name' => 'FUNCTIONAL MOVEMENT PACK',
                'description' => 'Functional movement and fitness packages for all levels',
                'sort_order' => 2,
                'is_active' => true
            ],
            [
                'name' => 'vip',
                'display_name' => 'VIP MEMBERSHIP',
                'description' => 'Exclusive VIP membership packages with premium benefits',
                'sort_order' => 3,
                'is_active' => true
            ],
            [
                'name' => 'special',
                'display_name' => 'SPECIAL PACKAGES',
                'description' => 'Limited time and special promotional packages',
                'sort_order' => 4,
                'is_active' => true
            ]
        ];

        foreach ($categories as $category) {
            PackageCategory::updateOrCreate(
                ['name' => $category['name']],
                $category
            );
        }
    }
}
