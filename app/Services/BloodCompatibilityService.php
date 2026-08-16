<?php

namespace App\Services;


class BloodCompatibilityService
{

    public static function compatibleDonorGroups($bloodGroup)
    {

        $compatibility = [

            'O-' => [
                'O-'
            ],

            'O+' => [
                'O+',
                'O-'
            ],

            'A-' => [
                'A-',
                'O-'
            ],

            'A+' => [
                'A+',
                'A-',
                'O+',
                'O-'
            ],

            'B-' => [
                'B-',
                'O-'
            ],

            'B+' => [
                'B+',
                'B-',
                'O+',
                'O-'
            ],

            'AB-' => [
                'AB-',
                'A-',
                'B-',
                'O-'
            ],

            'AB+' => [
                'A+',
                'A-',
                'B+',
                'B-',
                'AB+',
                'AB-',
                'O+',
                'O-'
            ],

        ];


        return $compatibility[$bloodGroup] ?? [];

    }

}