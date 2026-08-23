<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ReceptionistSeeder extends Seeder
{
    public function run(): void
    {
        $receptionists = [
            [
                'name' => 'Nusrat Jahan',
                'email' => 'nusrat.receptionist@gmail.com',
                'password' => 'password',
            ],
            [
                'name' => 'Farzana Akter',
                'email' => 'farzana.receptionist@gmail.com',
                'password' => 'password',
            ],
            [
                'name' => 'Mahmud Hasan',
                'email' => 'mahmud.receptionist@gmail.com',
                'password' => 'password',
            ],
        ];

        foreach ($receptionists as $receptionist) {
            User::updateOrCreate(
                ['email' => $receptionist['email']],
                [
                    'name' => $receptionist['name'],
                    'password' => Hash::make($receptionist['password']),
                    'role' => 'receptionist',
                ]
            );
        }
    }
}