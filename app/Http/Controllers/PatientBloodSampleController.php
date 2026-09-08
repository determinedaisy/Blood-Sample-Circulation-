<?php

namespace App\Http\Controllers;

use App\Models\BloodSample;
use App\Models\Donor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PatientBloodSampleController extends Controller
{
    /**
     * Display the patient's completed blood samples.
     *
     * Patients should see only samples that have received
     * a final laboratory result:
     *
     * - Accepted
     * - Rejected
     *
     * Pending samples belong to Sample Requests / workflow
     * and should not appear here.
     */
    public function index()
    {
        if (
            !auth()->check()
            || auth()->user()->role !== 'patient'
        ) {
            abort(403);
        }

        $bloodSamples = BloodSample::with([
            'patient',
            'donor',
            'collector',
            'reviewer',
            'sampleRequest',
            'sampleReport.results',
            'sampleReport.doctor',
            'transportations.transporter',
            'transportations.collectionCenter',
            'transportations.laboratory',
        ])
            ->where('patient_id', auth()->id())
            ->whereIn('status', [
                'accepted',
                'rejected',
            ])
            ->latest()
            ->get();

        return view(
            'patient.blood-samples.index',
            compact('bloodSamples')
        );
    }


    /**
     * Display the patient's blood sample donation form.
     */
    public function create()
    {
        if (
            !auth()->check()
            || auth()->user()->role !== 'patient'
        ) {
            abort(403);
        }

        return view('patient.blood-samples.create');
    }


    /**
     * Store a new blood sample donation.
     */
    public function store(Request $request): RedirectResponse
    {
        if (
            !auth()->check()
            || auth()->user()->role !== 'patient'
        ) {
            abort(403);
        }

        $validated = $request->validate([
            'sample_type' => [
                'required',
                'string',
                'max:255',
            ],

            'collected_at' => [
                'required',
                'date',
            ],
        ]);

        /*
         * A patient can also be a donor.
         *
         * Find the patient's existing donor profile.
         * If the patient does not have one yet,
         * create it automatically.
         */
        $patient = auth()->user();

        $patientProfile = $patient->patientProfile;

        $donor = Donor::firstOrCreate(
            [
                'user_id' => $patient->id,
            ],
            [
                'blood_group' => $patientProfile?->blood_group ?? 'Unknown',
                'phone' => $patientProfile?->phone ?? '',
                'is_willing' => true,
                'is_available' => true,
                'is_verified' => false,
                'donation_count' => 0,
                'donor_badge' => 'none',
                'shop_discount' => 0,
                'donor_priority' => 0,
            ]
        );

        /*
         * Generate a unique blood sample code.
         */
        do {
            $sampleCode = 'BS-' . strtoupper(Str::random(8));
        } while (
            BloodSample::where(
                'sample_code',
                $sampleCode
            )->exists()
        );

        /*
         * Create the blood sample.
         *
         * It starts as pending because it still
         * requires laboratory examination.
         */
        BloodSample::create([
            'sample_code' => $sampleCode,

            // The patient who owns the sample
            'patient_id' => $patient->id,

            // The donor who actually donated it
            'donor_id' => $donor->id,

            'collected_by' => null,

            'sample_type' => $validated['sample_type'],

            // New donations require laboratory review
            'status' => 'pending',

            'collected_at' => $validated['collected_at'],

            'quality_checks' => null,
            'rejection_reason' => null,
            'reviewed_by' => null,
            'reviewed_at' => null,
        ]);

        return redirect()
            ->route('patient.blood-samples.index')
            ->with(
                'success',
                'Your blood sample donation was submitted successfully and is awaiting laboratory review.'
            );
    }
}