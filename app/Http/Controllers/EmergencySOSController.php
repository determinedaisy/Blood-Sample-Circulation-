<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Donor;
use App\Models\EmergencyRequest;
use App\Models\DonorRequest;
use App\Services\BloodCompatibilityService;
use App\Services\DistanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmergencySOSController extends Controller
{
    /**
     * SOS donor search radius.
     *
     * Donors must be between 1 km and 2 km
     * from the patient's emergency location.
     */
    private const MIN_DISTANCE_KM = 1.0;
    private const MAX_DISTANCE_KM = 2.0;

    public function index()
    {
        return view('sos.index');
    }

    /**
     * Create emergency SOS request and find compatible donors.
     */
    public function store(Request $request)
    {
        $request->validate([
            'blood_group' => [
                'required',
                'string',
                'in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            ],

            'latitude' => [
                'required',
                'numeric',
                'between:-90,90',
            ],

            'longitude' => [
                'required',
                'numeric',
                'between:-180,180',
            ],
        ]);

        $patient = Patient::where(
            'user_id',
            Auth::id()
        )->first();

        if (!$patient) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Patient profile not found.'
                );
        }

        $requestedBloodGroup = strtoupper(
            trim(
                $request->input('blood_group')
            )
        );

        $requestLatitude = (float) $request->input('latitude');
        $requestLongitude = (float) $request->input('longitude');

        /*
        |--------------------------------------------------------------------------
        | Create Emergency Request
        |--------------------------------------------------------------------------
        */

        $emergencyRequest = EmergencyRequest::create([
            'patient_id' => $patient->id,
            'blood_group' => $requestedBloodGroup,
            'latitude' => $requestLatitude,
            'longitude' => $requestLongitude,
            'status' => 'searching',
        ]);

        /*
        |--------------------------------------------------------------------------
        | MEDICAL BLOOD COMPATIBILITY
        |--------------------------------------------------------------------------
        |
        | The BloodCompatibilityService must return donor blood groups
        | that are medically compatible with the recipient.
        |
        */

        $compatibleGroups =
            BloodCompatibilityService::compatibleDonorGroups(
                $requestedBloodGroup
            );

        /*
        |--------------------------------------------------------------------------
        | Find Compatible Donors
        |--------------------------------------------------------------------------
        */

        $donors = Donor::with('user')
            ->whereIn(
                'blood_group',
                $compatibleGroups
            )
            ->where(
                'is_willing',
                true
            )
            ->where(
                'is_available',
                true
            )
            ->whereNotNull(
                'latitude'
            )
            ->whereNotNull(
                'longitude'
            )
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Calculate Distance
        |--------------------------------------------------------------------------
        */

        $donors = $donors->map(
            function ($donor) use (
                $requestLatitude,
                $requestLongitude
            ) {

                $donor->distance =
                    DistanceService::calculate(
                        $requestLatitude,
                        $requestLongitude,
                        (float) $donor->latitude,
                        (float) $donor->longitude
                    );

                /*
                 * Road distance/time are calculated by the map/result page.
                 */
                $donor->road_distance_km = null;
                $donor->road_duration_minutes = null;

                return $donor;
            }
        );

        /*
        |--------------------------------------------------------------------------
        | Keep ONLY donors between 1 KM and 2 KM
        |--------------------------------------------------------------------------
        */

        $donors = $donors
            ->filter(
                function ($donor) {

                    return
                        $donor->distance !== null
                        &&
                        $donor->distance >= self::MIN_DISTANCE_KM
                        &&
                        $donor->distance <= self::MAX_DISTANCE_KM;
                }
            )
            ->sortBy(
                'distance'
            )
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Existing Donor Requests
        |--------------------------------------------------------------------------
        */

        $requestedDonors =
            DonorRequest::where(
                'patient_id',
                $patient->id
            )
                ->whereIn(
                    'status',
                    [
                        'pending',
                        'accepted',
                    ]
                )
                ->pluck(
                    'donor_id'
                )
                ->toArray();

        /*
        |--------------------------------------------------------------------------
        | Result Page
        |--------------------------------------------------------------------------
        */

        return view(
            'sos.result',
            [
                'emergencyRequest' =>
                    $emergencyRequest,

                'donors' =>
                    $donors,

                'requestedDonors' =>
                    $requestedDonors,

                'compatibleGroups' =>
                    $compatibleGroups,
            ]
        );
    }

    /**
     * Display the latest SOS donor results.
     */
    public function results()
    {
        $patient = Patient::where(
            'user_id',
            Auth::id()
        )->firstOrFail();

        $emergencyRequest =
            EmergencyRequest::where(
                'patient_id',
                $patient->id
            )
                ->latest()
                ->first();

        if (!$emergencyRequest) {
            return redirect()
                ->route('sos.index')
                ->with(
                    'error',
                    'No emergency SOS request found.'
                );
        }

        $requestedBloodGroup =
            strtoupper(
                trim(
                    $emergencyRequest->blood_group
                )
            );

        /*
        |--------------------------------------------------------------------------
        | Get ALL Medically Compatible Blood Groups
        |--------------------------------------------------------------------------
        */

        $compatibleGroups =
            BloodCompatibilityService::compatibleDonorGroups(
                $requestedBloodGroup
            );

        /*
        |--------------------------------------------------------------------------
        | Find Compatible Donors
        |--------------------------------------------------------------------------
        */

        $donors = Donor::with('user')
            ->whereIn(
                'blood_group',
                $compatibleGroups
            )
            ->where(
                'is_willing',
                true
            )
            ->where(
                'is_available',
                true
            )
            ->whereNotNull(
                'latitude'
            )
            ->whereNotNull(
                'longitude'
            )
            ->get();

        $requestLatitude =
            (float) $emergencyRequest->latitude;

        $requestLongitude =
            (float) $emergencyRequest->longitude;

        /*
        |--------------------------------------------------------------------------
        | Calculate Distance
        |--------------------------------------------------------------------------
        */

        $donors = $donors->map(
            function ($donor) use (
                $requestLatitude,
                $requestLongitude
            ) {

                $donor->distance =
                    DistanceService::calculate(
                        $requestLatitude,
                        $requestLongitude,
                        (float) $donor->latitude,
                        (float) $donor->longitude
                    );

                $donor->road_distance_km = null;
                $donor->road_duration_minutes = null;

                return $donor;
            }
        );

        /*
        |--------------------------------------------------------------------------
        | Keep ONLY donors between 1 KM and 2 KM
        |--------------------------------------------------------------------------
        */

        $donors = $donors
            ->filter(
                function ($donor) {

                    return
                        $donor->distance !== null
                        &&
                        $donor->distance >= self::MIN_DISTANCE_KM
                        &&
                        $donor->distance <= self::MAX_DISTANCE_KM;
                }
            )
            ->sortBy(
                'distance'
            )
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Existing Donor Requests
        |--------------------------------------------------------------------------
        */

        $requestedDonors =
            DonorRequest::where(
                'patient_id',
                $patient->id
            )
                ->whereIn(
                    'status',
                    [
                        'pending',
                        'accepted',
                    ]
                )
                ->pluck(
                    'donor_id'
                )
                ->toArray();

        /*
        |--------------------------------------------------------------------------
        | Result Page
        |--------------------------------------------------------------------------
        */

        return view(
            'sos.result',
            [
                'emergencyRequest' =>
                    $emergencyRequest,

                'donors' =>
                    $donors,

                'requestedDonors' =>
                    $requestedDonors,

                'compatibleGroups' =>
                    $compatibleGroups,
            ]
        );
    }
}