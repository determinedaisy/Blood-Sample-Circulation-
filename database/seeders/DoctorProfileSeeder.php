<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DoctorProfile;
use App\Models\User;

class DoctorProfileSeeder extends Seeder
{
    public function run(): void
    {
        $doctors = User::where('role', 'doctor')->get();

        foreach ($doctors as $doctor) {

            DoctorProfile::updateOrCreate(

                [
                    'user_id' => $doctor->id
                ],

                [
                    'license_number' => 'DOC-'.$doctor->id,
                    'specialization' => 'Hematology',
                    'hospital' => 'Blood System Hospital',
                    'phone' => '017000000'.$doctor->id,
                ]

            );
        }
    }
}