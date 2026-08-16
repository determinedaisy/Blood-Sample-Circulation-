<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Donor;

class DonorSeeder extends Seeder
{
    public function run(): void
    {
        Donor::create([
            'user_id' => 1,
            'blood_group' => 'O+',
            'phone' => '01700000001',
            'latitude' => 23.8103,
            'longitude' => 90.4125,
            'is_willing' => true,
            'is_available' => true,
            'is_verified' => true,
        ]);
    }
}