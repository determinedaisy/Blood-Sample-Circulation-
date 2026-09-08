<?php

namespace App\Http\Controllers;

use App\Models\Donor;
use App\Models\DonorRequest;
use App\Models\EmergencyRequest;
use App\Models\Patient;
use App\Notifications\BloodDonationRequestNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DonorRequestController extends Controller
{
    /**
     * Patient sends a blood donation request to a donor.
     */
    public function store(Request $request, $donorId)
    {
        $patient = Patient::where(
            'user_id',
            Auth::id()
        )->firstOrFail();

        $donor = Donor::with('user')
            ->findOrFail($donorId);

        $emergencyRequest = EmergencyRequest::where(
            'patient_id',
            $patient->id
        )
            ->whereIn(
                'status',
                [
                    'searching',
                    'pending',
                    'accepted',
                    'tracking',
                ]
            )
            ->latest()
            ->first();

        if (!$emergencyRequest) {
            return back()->with(
                'error',
                'No active emergency request found.'
            );
        }

        $existing = DonorRequest::where(
            'emergency_request_id',
            $emergencyRequest->id
        )
            ->where(
                'donor_id',
                $donor->id
            )
            ->whereIn(
                'status',
                [
                    'pending',
                    'accepted',
                ]
            )
            ->first();

        if ($existing) {
            return redirect()
                ->route('sos.results')
                ->with(
                    'error',
                    'You have already requested this donor.'
                );
        }

        $donorRequest = DB::transaction(
            function () use (
                $emergencyRequest,
                $patient,
                $donor
            ) {
                $donorRequest = DonorRequest::create([
                    'emergency_request_id' =>
                        $emergencyRequest->id,

                    'patient_id' =>
                        $patient->id,

                    'donor_id' =>
                        $donor->id,

                    'status' =>
                        'pending',

                    'patient_latitude' =>
                        $emergencyRequest->latitude,

                    'patient_longitude' =>
                        $emergencyRequest->longitude,

                    'donor_latitude' =>
                        $donor->latitude,

                    'donor_longitude' =>
                        $donor->longitude,
                ]);

                $emergencyRequest->update([
                    'status' => 'pending',
                ]);

                return $donorRequest;
            }
        );

        if ($donor->user) {
            $donor->user->notify(
                new BloodDonationRequestNotification(
                    $donorRequest
                )
            );
        }

        return redirect()
            ->route('sos.results')
            ->with(
                'success',
                'Blood request sent to the donor successfully.'
            );
    }

    /**
     * Show incoming emergency blood requests to the donor.
     */
    public function incoming()
    {
        $donor = Donor::where(
            'user_id',
            Auth::id()
        )->firstOrFail();

        $requests = DonorRequest::with([
            'patient.user',
            'emergencyRequest',
        ])
            ->where(
                'donor_id',
                $donor->id
            )
            ->where(
                'status',
                'pending'
            )
            ->latest()
            ->get();

        $notifications = Auth::user()
            ->unreadNotifications()
            ->where(
                'type',
                BloodDonationRequestNotification::class
            )
            ->latest()
            ->get();

        return view(
            'donor.requests',
            compact(
                'requests',
                'notifications'
            )
        );
    }

    /**
     * Donor accepts an emergency blood request.
     */
    public function accept($id)
    {
        $donor = Donor::where(
            'user_id',
            Auth::id()
        )->firstOrFail();

        $donorRequest = DonorRequest::with([
            'emergencyRequest',
            'patient.user',
        ])
            ->where(
                'id',
                $id
            )
            ->where(
                'donor_id',
                $donor->id
            )
            ->where(
                'status',
                'pending'
            )
            ->firstOrFail();

        $donorRequest->update([
            'status' => 'accepted',

            'accepted_at' => now(),

            'donor_latitude' =>
                $donor->latitude,

            'donor_longitude' =>
                $donor->longitude,
        ]);

        if ($donorRequest->emergencyRequest) {
            $donorRequest->emergencyRequest->update([
                'status' => 'accepted',
            ]);
        }

        Auth::user()
            ->unreadNotifications()
            ->where(
                'type',
                BloodDonationRequestNotification::class
            )
            ->where(
                'data->donor_request_id',
                $donorRequest->id
            )
            ->update([
                'read_at' => now(),
            ]);

        return redirect()
            ->route(
                'donor.request.track',
                $donorRequest->id
            )
            ->with(
                'success',
                'Blood request accepted. Tracking has started.'
            );
    }

    /**
     * Donor declines an emergency blood request.
     */
    public function decline($id)
    {
        $donor = Donor::where(
            'user_id',
            Auth::id()
        )->firstOrFail();

        $donorRequest = DonorRequest::where(
            'id',
            $id
        )
            ->where(
                'donor_id',
                $donor->id
            )
            ->where(
                'status',
                'pending'
            )
            ->firstOrFail();

        $donorRequest->update([
            'status' => 'declined',
        ]);

        Auth::user()
            ->unreadNotifications()
            ->where(
                'type',
                BloodDonationRequestNotification::class
            )
            ->where(
                'data->donor_request_id',
                $donorRequest->id
            )
            ->update([
                'read_at' => now(),
            ]);

        return redirect()
            ->route('donor.requests')
            ->with(
                'success',
                'Blood request declined.'
            );
    }

    /**
     * Show the tracking page for an accepted request.
     */
    public function track($id)
    {
        $donor = Donor::where(
            'user_id',
            Auth::id()
        )->firstOrFail();

        $donorRequest = DonorRequest::with([
            'patient.user',
            'emergencyRequest',
            'donor.user',
        ])
            ->where(
                'id',
                $id
            )
            ->where(
                'donor_id',
                $donor->id
            )
            ->where(
                'status',
                'accepted'
            )
            ->firstOrFail();

        return view(
            'donor.track',
            compact('donorRequest')
        );
    }

    /**
     * Update the donor's live GPS location.
     */
    public function updateLocation(
        Request $request,
        $id
    ) {
        $request->validate([
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

        $donor = Donor::where(
            'user_id',
            Auth::id()
        )->firstOrFail();

        $donorRequest = DonorRequest::where(
            'id',
            $id
        )
            ->where(
                'donor_id',
                $donor->id
            )
            ->where(
                'status',
                'accepted'
            )
            ->firstOrFail();

        $donor->update([
            'latitude' =>
                $request->latitude,

            'longitude' =>
                $request->longitude,
        ]);

        $donorRequest->update([
            'donor_latitude' =>
                $request->latitude,

            'donor_longitude' =>
                $request->longitude,
        ]);

        return response()->json([
            'success' => true,
        ]);
    }

    /**
     * Complete the emergency blood donation.
     *
     * A completed emergency donation counts as
     * one successful donation for the donor.
     */
    public function complete($id)
    {
        $donor = Donor::where(
            'user_id',
            Auth::id()
        )->firstOrFail();

        $donorRequest = DonorRequest::with(
            'emergencyRequest'
        )
            ->where(
                'id',
                $id
            )
            ->where(
                'donor_id',
                $donor->id
            )
            ->where(
                'status',
                'accepted'
            )
            ->firstOrFail();

        DB::transaction(
            function () use (
                $donor,
                $donorRequest
            ) {
                $donorRequest->update([
                    'status' =>
                        'completed',

                    'completed_at' =>
                        now(),
                ]);

                if (
                    $donorRequest->emergencyRequest
                ) {
                    $donorRequest
                        ->emergencyRequest
                        ->update([
                            'status' =>
                                'completed',
                        ]);
                }

                $donor->refresh();

                $donor->updateBadge();
            }
        );

        return redirect()
            ->route('donor.requests')
            ->with(
                'success',
                'Emergency blood donation completed successfully. Your donation count, badge, discount and donor priority have been updated.'
            );
    }
}
