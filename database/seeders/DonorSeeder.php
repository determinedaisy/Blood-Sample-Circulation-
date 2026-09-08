<?php

namespace Database\Seeders;

use App\Models\Donor;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DonorSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Emergency SOS Test Location
        |--------------------------------------------------------------------------
        |
        | Patient/SOS test location:
        | Latitude:  23.8103
        | Longitude: 90.4125
        |
        | All donors are intentionally placed within approximately 2 km
        | of this location so the Emergency SOS compatibility system
        | can be properly tested.
        |
        */

        $donors = [

            [
                'name' => 'Karim Donor',
                'email' => 'karim.donor@gmail.com',
                'blood_group' => 'B+',
                'phone' => '01710000001',

                // Approximately 1.2 km north
                'latitude' => 23.8210000,
                'longitude' => 90.4125000,
            ],

            [
                'name' => 'Sakib Donor',
                'email' => 'sakib.donor@gmail.com',
                'blood_group' => 'O+',
                'phone' => '01710000002',

                // Approximately 1.4 km east
                'latitude' => 23.8103000,
                'longitude' => 90.4262000,
            ],

            [
                'name' => 'Rahim Donor',
                'email' => 'rahim.donor@gmail.com',
                'blood_group' => 'A+',
                'phone' => '01710000003',

                // Approximately 1.7 km northeast
                'latitude' => 23.8210000,
                'longitude' => 90.4245000,
            ],

            [
                'name' => 'Nabil Donor',
                'email' => 'nabil.donor@gmail.com',
                'blood_group' => 'A-',
                'phone' => '01710000004',

                // Approximately 1.3 km south
                'latitude' => 23.7986000,
                'longitude' => 90.4125000,
            ],

            [
                'name' => 'Tanvir Donor',
                'email' => 'tanvir.donor@gmail.com',
                'blood_group' => 'B-',
                'phone' => '01710000005',

                // Approximately 1.5 km west
                'latitude' => 23.8103000,
                'longitude' => 90.3978000,
            ],

            [
                'name' => 'Farhan Donor',
                'email' => 'farhan.donor@gmail.com',
                'blood_group' => 'AB+',
                'phone' => '01710000006',

                // Approximately 1.8 km northwest
                'latitude' => 23.8235000,
                'longitude' => 90.3978000,
            ],

            [
                'name' => 'Imran Donor',
                'email' => 'imran.donor@gmail.com',
                'blood_group' => 'AB-',
                'phone' => '01710000007',

                // Approximately 1.7 km southeast
                'latitude' => 23.7986000,
                'longitude' => 90.4245000,
            ],

            [
                'name' => 'Shakil Donor',
                'email' => 'shakil.donor@gmail.com',
                'blood_group' => 'O-',
                'phone' => '01710000008',

                // Approximately 1.9 km southwest
                'latitude' => 23.7986000,
                'longitude' => 90.3978000,
            ],

        ];

        foreach ($donors as $donorData) {

            $user = User::updateOrCreate(
                [
                    'email' => $donorData['email'],
                ],
                [
                    'name' => $donorData['name'],
                    'email' => $donorData['email'],
                    'password' => Hash::make('password'),
                    'role' => 'donor',
                ]
            );

            Donor::updateOrCreate(
                [
                    'user_id' => $user->id,
                ],
                [
                    'blood_group' => $donorData['blood_group'],
                    'phone' => $donorData['phone'],
                    'latitude' => $donorData['latitude'],
                    'longitude' => $donorData['longitude'],

                    'is_willing' => true,
                    'is_available' => true,
                    'is_verified' => true,

                    'donation_count' => 0,
                    'donor_badge' => 'none',
                    'shop_discount' => 0,
                    'donor_priority' => 0,
                ]
            );
        }
    }
}