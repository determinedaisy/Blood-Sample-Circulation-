<?php

namespace App\Http\Controllers;

use App\Notifications\BloodSampleRejectedNotification;
use App\Http\Requests\ReviewBloodSampleRequest;
use App\Models\BloodSample;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class BloodSampleReviewController extends Controller
{
    /**
     * Accept or reject a blood sample.
     */

    public function index()
{
    if (
        ! auth()->check()
        || ! in_array(
            auth()->user()->role,
            ['lab_staff', 'admin'],
            true
        )
    ) {
        abort(403);
    }

    $bloodSamples = BloodSample::with([
        'patient',
        'collector',
        'reviewer'
    ])
    ->latest()
    ->get();

    return view(
        'blood-samples.index',
        compact('bloodSamples')
    );
}
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

        return back()->with(
            'success',
            'Blood sample accepted successfully.'
        );
    }
}