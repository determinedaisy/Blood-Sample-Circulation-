<?php

namespace App\Http\Controllers;

use App\Models\BloodSample;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PatientBloodSampleController extends Controller
{
    /**
     * Display the patient's blood samples.
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
            'patient', // Added this line to fetch patient details for the QR Code
            'collector',
            'reviewer',
            'sampleRequest',
            'transportations.transporter',
            'transportations.collectionCenter',
            'transportations.laboratory',
        ])
        ->where('patient_id', auth()->id())
        ->latest()
        ->get();

        return view(
            'patient.blood-samples.index',
            compact('bloodSamples')
        );
    }

    /**
     * Display the blood sample donation form.
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

        do {
            $sampleCode = 'BS-' . strtoupper(Str::random(8));
        } while (
            BloodSample::where(
                'sample_code',
                $sampleCode
            )->exists()
        );

        BloodSample::create([
            'sample_code' => $sampleCode,

            // The logged-in patient is the owner
            'patient_id' => auth()->id(),

            // No separate collector for patient self-donation
            'collected_by' => null,

            'sample_type' => $validated['sample_type'],

            // New donations must be reviewed first
            'status' => 'pending',

            'collected_at' => $validated['collected_at'],

            // These are filled in later by laboratory staff
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