<?php

namespace App\Http\Controllers;

use App\Models\BloodSample;

class PatientBloodSampleController extends Controller
{
    public function index()
    {
        if (
            ! auth()->check()
            || auth()->user()->role !== 'patient'
        ) {
            abort(403);
        }

        $bloodSamples = BloodSample::with([
            'collector',
            'reviewer'
        ])
        ->where('patient_id', auth()->id())
        ->latest()
        ->get();

        return view(
            'patient.blood-samples.index',
            compact('bloodSamples')
        );
    }
}
