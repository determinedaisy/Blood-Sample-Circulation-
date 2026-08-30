<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewBloodSampleRequest;
use App\Models\BloodSample;
use App\Notifications\BloodSampleAcceptedNotification;
use App\Notifications\BloodSampleRejectedNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class BloodSampleReviewController extends Controller
{
    /**
     * Show blood samples for review.
     */
    public function index()
    {
        $bloodSamples = BloodSample::with([
            'patient',
            'donor',
            'collector',
            'reviewer',
            'transportations.transporter',
            'transportations.collectionCenter',
            'transportations.laboratory',
        ])->get();

        return view(
            'blood-samples.index',
            compact('bloodSamples')
        );
    }

    /**
     * Accept or reject a blood sample.
     */
    public function update(
        ReviewBloodSampleRequest $request,
        BloodSample $bloodSample
    ): RedirectResponse {

        $data = $request->validated();

        DB::transaction(function () use ($data, $bloodSample) {

            /*
            |--------------------------------------------------------------------------
            | Update Blood Sample Review
            |--------------------------------------------------------------------------
            */

            $bloodSample->update([
                'status' => $data['decision'],

                'quality_checks' =>
                    $data['quality_checks'] ?? null,

                'rejection_reason' =>
                    $data['decision'] === 'rejected'
                        ? $data['rejection_reason']
                        : null,

                'reviewed_by' => auth()->id(),

                'reviewed_at' => now(),
            ]);

            /*
            |--------------------------------------------------------------------------
            | Rejected Donation
            |--------------------------------------------------------------------------
            |
            | Rejection notifications are sent to the patient who submitted
            | the sample, if a patient is associated with it.
            |
            */

            if ($data['decision'] === 'rejected') {

                $bloodSample->load('patient');

                if ($bloodSample->patient) {

                    $bloodSample->patient->notify(
                        new BloodSampleRejectedNotification($bloodSample)
                    );
                }

                return;
            }

            /*
            |--------------------------------------------------------------------------
            | Accepted Donation - Feature 20
            |--------------------------------------------------------------------------
            |
            | Only accepted donations count toward:
            |
            | - donation_count
            | - donor_badge
            | - shop_discount
            | - donor_priority
            |
            | The Donor model owns these values.
            |
            */

            if ($data['decision'] === 'accepted') {

                $bloodSample->load('donor');

                if ($bloodSample->donor) {

                    /*
                    | Recalculate the donor's badge first.
                    */
                    $bloodSample->donor->updateBadge();

                    /*
                    | Refresh the donor so the notification receives
                    | the newly calculated badge/count/discount.
                    */
                    $bloodSample->donor->refresh();

                    /*
                    |--------------------------------------------------------------------------
                    | Send accepted donation notification
                    |--------------------------------------------------------------------------
                    |
                    | The donor is related to a User through user_id.
                    | Notifications are stored in the users table's
                    | notifications relationship.
                    |
                    */
                    $bloodSample->donor->user?->notify(
                        new BloodSampleAcceptedNotification($bloodSample)
                    );
                }
            }
        });

        /*
        |--------------------------------------------------------------------------
        | Response Messages
        |--------------------------------------------------------------------------
        */

        if ($data['decision'] === 'rejected') {

            return back()->with(
                'warning',
                'Blood sample rejected successfully. The patient has been notified.'
            );
        }

        return back()->with(
            'success',
            'Blood sample accepted successfully. Donor badge has been updated and the donor has been notified.'
        );
    }
}
