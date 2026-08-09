<?php

namespace Database\Seeders;

use App\Models\CollectionCenter;
use Illuminate\Database\Seeder;

class CollectionCenterSeeder extends Seeder
{
    public function run(): void
    {
        $centers = [
            [
                'name' => 'Dhanmondi Collection Center',
                'address' => 'Dhanmondi, Dhaka',
                'phone' => '01710000001',
                'is_active' => true,
            ],
            [
                'name' => 'Uttara Collection Center',
                'address' => 'Uttara, Dhaka',
                'phone' => '01710000002',
                'is_active' => true,
            ],
            [
                'name' => 'Mirpur Collection Center',
                'address' => 'Mirpur, Dhaka',
                'phone' => '01710000003',
                'is_active' => true,
            ],
            [
                'name' => 'Banani Collection Center',
                'address' => 'Banani, Dhaka',
                'phone' => '01710000004',
                'is_active' => true,
            ],
            [
                'name' => 'Mohakhali Collection Center',
                'address' => 'Mohakhali, Dhaka',
                'phone' => '01710000005',
                'is_active' => true,
            ],
        ];

        foreach ($centers as $center) {
            CollectionCenter::updateOrCreate(
                ['name' => $center['name']],
                $center
            );
        }
    }
}