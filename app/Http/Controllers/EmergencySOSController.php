<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Donor;
use App\Models\EmergencyRequest;
use App\Services\BloodCompatibilityService;
use App\Services\DistanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmergencySOSController extends Controller
{
    public function index()
    {
        return view('sos.index');
    }


    public function store(Request $request)
    {
        $request->validate([
            'latitude' => 'required',
            'longitude' => 'required',
        ]);


        $patient = Patient::where(
            'user_id',
            Auth::id()
        )->first();


        if (!$patient) {

            return back()->with(
                'error',
                'Patient profile not found.'
            );

        }


        // Find blood groups that can donate to this patient
        $compatibleGroups =
            BloodCompatibilityService::compatibleDonorGroups(
                $patient->blood_group
            );


        // Find compatible, willing, available and verified donors
        $donors = Donor::whereIn(
                'blood_group',
                $compatibleGroups
            )
            ->where('is_willing', true)
            ->where('is_available', true)
            ->where('is_verified', true)
            ->get();


        // Calculate distance from patient to every donor
        foreach ($donors as $donor) {

            $donor->distance = DistanceService::calculate(
                $request->latitude,
                $request->longitude,
                $donor->latitude,
                $donor->longitude
            );

        }


        // Sort nearest donor first
        $donors = $donors->sortBy('distance');


        // Create emergency request
        EmergencyRequest::create([
            'patient_id' => $patient->id,
            'blood_group' => $patient->blood_group,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'status' => 'searching',
        ]);


        // Show donor results immediately
        return view('sos.result', [
            'donors' => $donors,
            'requestedDonors' => [],
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Show SOS Results
    |--------------------------------------------------------------------------
    */

    public function results()
    {
        $patient = Patient::where(
            'user_id',
            Auth::id()
        )->firstOrFail();


        $compatibleGroups =
            BloodCompatibilityService::compatibleDonorGroups(
                $patient->blood_group
            );


        $donors = Donor::whereIn(
                'blood_group',
                $compatibleGroups
            )
            ->where('is_willing', true)
            ->where('is_available', true)
            ->where('is_verified', true)
            ->get();


        return view('sos.result', [

            'donors' => $donors,

            'requestedDonors' => \App\Models\DonorRequest::where(
                'patient_id',
                $patient->id
            )->pluck('donor_id')->toArray()

        ]);
    }
}