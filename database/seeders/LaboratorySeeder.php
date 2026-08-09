<?php

namespace Database\Seeders;

use App\Models\Laboratory;
use Illuminate\Database\Seeder;

class LaboratorySeeder extends Seeder
{
    public function run(): void
    {
        $laboratories = [
            [
                'name' => 'Central Diagnostic Laboratory',
                'address' => 'Dhanmondi, Dhaka',
                'phone' => '01810000001',
                'daily_capacity' => 100,
                'is_active' => true,
            ],
            [
                'name' => 'Uttara Medical Laboratory',
                'address' => 'Uttara, Dhaka',
                'phone' => '01810000002',
                'daily_capacity' => 80,
                'is_active' => true,
            ],
            [
                'name' => 'Mirpur Diagnostic Laboratory',
                'address' => 'Mirpur, Dhaka',
                'phone' => '01810000003',
                'daily_capacity' => 70,
                'is_active' => true,
            ],
            [
                'name' => 'Banani Medical Laboratory',
                'address' => 'Banani, Dhaka',
                'phone' => '01810000004',
                'daily_capacity' => 60,
                'is_active' => true,
            ],
        ];

        foreach ($laboratories as $laboratory) {
            Laboratory::updateOrCreate(
                ['name' => $laboratory['name']],
                $laboratory
            );
        }
    }
}