<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewBloodSampleRequest;
use App\Models\BloodSample;
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
        });


        /*
        |--------------------------------------------------------------------------
        | Rejected
        |--------------------------------------------------------------------------
        */

        if ($data['decision'] === 'rejected') {

            $bloodSample->load('patient');

            if ($bloodSample->patient) {

                $bloodSample->patient->notify(
                    new BloodSampleRejectedNotification($bloodSample)
                );
            }

            return back()->with(
                'warning',
                'Blood sample rejected successfully. The patient has been notified.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Accepted
        |--------------------------------------------------------------------------
        */

        return back()->with(
            'success',
            'Blood sample accepted successfully.'
        );
    }
}