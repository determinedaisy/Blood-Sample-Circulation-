<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use App\Models\Donor;
use App\Models\User;


class DonorSeeder extends Seeder
{
    public function run(): void
    {


        $karim = User::firstOrCreate(
            [
                'email' => 'karim.donor@gmail.com'
            ],
            [
                'name' => 'Karim Donor',
                'password' => bcrypt('password'),
                'role' => 'donor'
            ]
        );


        Donor::firstOrCreate(

            [
                'user_id' => $karim->id
            ],

            [

                'blood_group' => 'B+',

                'phone' => '01722222222',

                'latitude' => 23.8103,

                'longitude' => 90.4125,

                'is_willing' => true,

                'is_available' => true,

                'is_verified' => true,

            ]

        );





        $sakib = User::firstOrCreate(
            [
                'email' => 'sakib.donor@gmail.com'
            ],
            [
                'name' => 'Sakib Donor',
                'password' => bcrypt('password'),
                'role' => 'donor'
            ]
        );



        Donor::firstOrCreate(

            [
                'user_id' => $sakib->id
            ],

            [

                'blood_group' => 'O-',

                'phone' => '01733333333',

                'latitude' => 23.8150,

                'longitude' => 90.4200,

                'is_willing' => true,

                'is_available' => true,

                'is_verified' => true,

            ]

        );





        $rahim = User::firstOrCreate(
            [
                'email' => 'rahim.donor@gmail.com'
            ],
            [
                'name' => 'Rahim Donor',
                'password' => bcrypt('password'),
                'role' => 'donor'
            ]
        );



        Donor::firstOrCreate(

            [
                'user_id' => $rahim->id
            ],

            [

                'blood_group' => 'A+',

                'phone' => '01711111111',

                'latitude' => 23.8200,

                'longitude' => 90.4300,

                'is_willing' => true,

                'is_available' => true,

                'is_verified' => true,

            ]

        );


    }
}