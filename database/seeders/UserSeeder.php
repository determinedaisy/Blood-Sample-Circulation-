<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insertOrIgnore([
            
            // Admins
            [
                'name' => 'System Admin',
                'email' => 'admin1@bloodsystem.com',
                'role' => 'admin',
                'password' => Hash::make('password123'),
            ],

            [
                'name' => 'Nusrat Jahan',
                'email' => 'nusrat.admin@bloodsystem.com',
                'role' => 'admin',
                'password' => Hash::make('password123'),
            ],
            [
    'name' => 'Rakib Hasan',
    'email' => 'rakib.admin@bloodsystem.com',
    'role' => 'admin',
    'password' => Hash::make('password123'),
],

[
    'name' => 'Farzana Islam',
    'email' => 'farzana.admin@bloodsystem.com',
    'role' => 'admin',
    'password' => Hash::make('password123'),
],

[
    'name' => 'Sabbir Ahmed',
    'email' => 'sabbir.admin@bloodsystem.com',
    'role' => 'admin',
    'password' => Hash::make('password123'),
],

[
    'name' => 'Mehedi Karim',
    'email' => 'mehedi.admin@bloodsystem.com',
    'role' => 'admin',
    'password' => Hash::make('password123'),
],

[
    'name' => 'Tania Rahman',
    'email' => 'tania.admin@bloodsystem.com',
    'role' => 'admin',
    'password' => Hash::make('password123'),
],

[
    'name' => 'Imran Hossain',
    'email' => 'imran.admin@bloodsystem.com',
    'role' => 'admin',
    'password' => Hash::make('password123'),
],

[
    'name' => 'Sadia Akter',
    'email' => 'sadia.admin@bloodsystem.com',
    'role' => 'admin',
    'password' => Hash::make('password123'),
],

[
    'name' => 'Arif Chowdhury',
    'email' => 'arif.admin@bloodsystem.com',
    'role' => 'admin',
    'password' => Hash::make('password123'),
],             
            // Doctors
            [
                'name' => 'Dr. Ahmed Rahman',
                'email' => 'ahmed.doctor@bloodsystem.com',
                'role' => 'doctor',
                'password' => Hash::make('password123'),
            ],
            [
    'name' => 'Dr. Sara Islam',
    'email' => 'sara.doctor@bloodsystem.com',
    'role' => 'doctor',
    'password' => Hash::make('password123'),
],

[
    'name' => 'Dr. Mahmud Hasan',
    'email' => 'mahmud.doctor@bloodsystem.com',
    'role' => 'doctor',
    'password' => Hash::make('password123'),
],

[
    'name' => 'Dr. Rafiq Karim',
    'email' => 'rafiq.doctor@bloodsystem.com',
    'role' => 'doctor',
    'password' => Hash::make('password123'),
],

[
    'name' => 'Dr. Jannatul Ferdous',
    'email' => 'jannatul.doctor@bloodsystem.com',
    'role' => 'doctor',
    'password' => Hash::make('password123'),
],

[
    'name' => 'Dr. Tanvir Ahmed',
    'email' => 'tanvir.doctor@bloodsystem.com',
    'role' => 'doctor',
    'password' => Hash::make('password123'),
],

[
    'name' => 'Dr. Nusrat Sultana',
    'email' => 'nusrat.doctor@bloodsystem.com',
    'role' => 'doctor',
    'password' => Hash::make('password123'),
],

[
    'name' => 'Dr. Faisal Khan',
    'email' => 'faisal.doctor@bloodsystem.com',
    'role' => 'doctor',
    'password' => Hash::make('password123'),
],

[
    'name' => 'Dr. Sharmeen Akter',
    'email' => 'sharmeen.doctor@bloodsystem.com',
    'role' => 'doctor',
    'password' => Hash::make('password123'),
],

[
    'name' => 'Dr. Kamal Hossain',
    'email' => 'kamal.doctor@bloodsystem.com',
    'role' => 'doctor',
    'password' => Hash::make('password123'),
],

            // Patients
            [
                'name' => 'Rahim Uddin',
                'email' => 'rahim.patient@gmail.com',
                'role' => 'patient',
                'password' => Hash::make('password123'),
            ],
            [
    'name' => 'Karim Mia',
    'email' => 'karim.patient@gmail.com',
    'role' => 'patient',
    'password' => Hash::make('password123'),
],

[
    'name' => 'Sumi Akter',
    'email' => 'sumi.patient@gmail.com',
    'role' => 'patient',
    'password' => Hash::make('password123'),
],

[
    'name' => 'Rohan Ahmed',
    'email' => 'rohan.patient@gmail.com',
    'role' => 'patient',
    'password' => Hash::make('password123'),
],

[
    'name' => 'Mim Rahman',
    'email' => 'mim.patient@gmail.com',
    'role' => 'patient',
    'password' => Hash::make('password123'),
],

[
    'name' => 'Hasan Ali',
    'email' => 'hasan.patient@gmail.com',
    'role' => 'patient',
    'password' => Hash::make('password123'),
],

[
    'name' => 'Tania Islam',
    'email' => 'tania.patient@gmail.com',
    'role' => 'patient',
    'password' => Hash::make('password123'),
],

[
    'name' => 'Jubayer Khan',
    'email' => 'jubayer.patient@gmail.com',
    'role' => 'patient',
    'password' => Hash::make('password123'),
],

[
    'name' => 'Nabila Noor',
    'email' => 'nabila.patient@gmail.com',
    'role' => 'patient',
    'password' => Hash::make('password123'),
],

[
    'name' => 'Fahim Hossain',
    'email' => 'fahim.patient@gmail.com',
    'role' => 'patient',
    'password' => Hash::make('password123'),
],

            // Lab Staff
            [
                'name' => 'Hasan Lab Technician',
                'email' => 'hasan.lab@gmail.com',
                'role' => 'lab_staff',
                'password' => Hash::make('password123'),
            ],
            [
    'name' => 'Rasel Ahmed',
    'email' => 'rasel.lab@gmail.com',
    'role' => 'lab_staff',
    'password' => Hash::make('password123'),
],

[
    'name' => 'Mitu Sarker',
    'email' => 'mitu.lab@gmail.com',
    'role' => 'lab_staff',
    'password' => Hash::make('password123'),
],

[
    'name' => 'Shakil Khan',
    'email' => 'shakil.lab@gmail.com',
    'role' => 'lab_staff',
    'password' => Hash::make('password123'),
],

[
    'name' => 'Priya Das',
    'email' => 'priya.lab@gmail.com',
    'role' => 'lab_staff',
    'password' => Hash::make('password123'),
],

[
    'name' => 'Nayeem Hasan',
    'email' => 'nayeem.lab@gmail.com',
    'role' => 'lab_staff',
    'password' => Hash::make('password123'),
],

[
    'name' => 'Ruma Akter',
    'email' => 'ruma.lab@gmail.com',
    'role' => 'lab_staff',
    'password' => Hash::make('password123'),
],

[
    'name' => 'Bashir Ahmed',
    'email' => 'bashir.lab@gmail.com',
    'role' => 'lab_staff',
    'password' => Hash::make('password123'),
],

[
    'name' => 'Sakib Islam',
    'email' => 'sakib.lab@gmail.com',
    'role' => 'lab_staff',
    'password' => Hash::make('password123'),
],

[
    'name' => 'Morshed Karim',
    'email' => 'morshed.lab@gmail.com',
    'role' => 'lab_staff',
    'password' => Hash::make('password123'),
],

            // Sample Collector
            [
                'name' => 'Karim Hossain',
                'email' => 'karim.collector@gmail.com',
                'role' => 'sample_collector',
                'password' => Hash::make('password123'),
            ],
            [
    'name' => 'Rony Mia',
    'email' => 'rony.collector@gmail.com',
    'role' => 'sample_collector',
    'password' => Hash::make('password123'),
],

[
    'name' => 'Babul Ahmed',
    'email' => 'babul.collector@gmail.com',
    'role' => 'sample_collector',
    'password' => Hash::make('password123'),
],

[
    'name' => 'Sohan Rahman',
    'email' => 'sohan.collector@gmail.com',
    'role' => 'sample_collector',
    'password' => Hash::make('password123'),
],

[
    'name' => 'Nadim Khan',
    'email' => 'nadim.collector@gmail.com',
    'role' => 'sample_collector',
    'password' => Hash::make('password123'),
],

[
    'name' => 'Rafi Islam',
    'email' => 'rafi.collector@gmail.com',
    'role' => 'sample_collector',
    'password' => Hash::make('password123'),
],

[
    'name' => 'Shuvo Das',
    'email' => 'shuvo.collector@gmail.com',
    'role' => 'sample_collector',
    'password' => Hash::make('password123'),
],

[
    'name' => 'Masud Rana',
    'email' => 'masud.collector@gmail.com',
    'role' => 'sample_collector',
    'password' => Hash::make('password123'),
],

[
    'name' => 'Anik Hasan',
    'email' => 'anik.collector@gmail.com',
    'role' => 'sample_collector',
    'password' => Hash::make('password123'),
],

[
    'name' => 'Jewel Ahmed',
    'email' => 'jewel.collector@gmail.com',
    'role' => 'sample_collector',
    'password' => Hash::make('password123'),
],

        ]);
    }
}