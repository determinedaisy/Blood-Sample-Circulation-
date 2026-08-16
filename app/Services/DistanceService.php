<?php

namespace App\Services;


class DistanceService
{

    public static function calculate(
        $lat1,
        $lon1,
        $lat2,
        $lon2
    ) {

        $earthRadius = 6371; // KM


        $latDifference = deg2rad($lat2 - $lat1);

        $lonDifference = deg2rad($lon2 - $lon1);



        $a =
            sin($latDifference / 2) *
            sin($latDifference / 2)

            +

            cos(deg2rad($lat1)) *
            cos(deg2rad($lat2)) *
            sin($lonDifference / 2) *
            sin($lonDifference / 2);



        $c = 2 * atan2(
            sqrt($a),
            sqrt(1 - $a)
        );


        return round(
            $earthRadius * $c,
            2
        );

    }

}