<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\BloodSample;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // 1. Call existing seeders
        $this->call([
            DoctorProfileSeeder::class,
            DonorSeeder::class,
        ]);

        // 2. Ensure System Admin exists for testing Feature 5
        User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'System Admin',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        // 3. Test User
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
            ]
        );

        // 4. Generate functioning Blood Samples data for charts and dashboard
        $statuses = ['accepted', 'rejected', 'pending'];
        
        for ($i = 1; $i <= 30; $i++) {
            // Mix of recent samples and samples older than 42 days to test AI expiration
            $daysOld = $i % 5 === 0 ? rand(45, 60) : rand(1, 10);
            
            BloodSample::create([
                'sample_code' => 'BS-2026-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'status' => $statuses[array_rand($statuses)],
                'created_at' => Carbon::now()->subDays($daysOld),
                'updated_at' => Carbon::now()->subDays($daysOld),
            ]);
        }
    }
}