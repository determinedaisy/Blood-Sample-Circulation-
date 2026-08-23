<?php

namespace App\Http\Controllers;

use App\Models\BloodSample;
use App\Models\ReceptionRequest;
use App\Models\SampleRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReceptionRequestController extends Controller
{
    /**
     * Patient: show the Contact Receptionist form.
     */
    public function create()
    {
        if (!Auth::check() || Auth::user()->role !== 'patient') {
            abort(403);
        }

        return view('reception-requests.create');
    }


    /**
     * Patient: send details to reception.
     */
    public function store(Request $request): RedirectResponse
    {
        if (!Auth::check() || Auth::user()->role !== 'patient') {
            abort(403);
        }

        $validated = $request->validate([
            'sample_type' => [
                'required',
                'string',
                'max:100',
            ],

            'blood_type' => [
                'nullable',
                'string',
                'max:10',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        ReceptionRequest::create([
            'patient_id' => Auth::id(),
            'sample_type' => $validated['sample_type'],
            'blood_type' => $validated['blood_type'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()
            ->route('reception-requests.patient.index')
            ->with(
                'success',
                'Your request has been sent to reception.'
            );
    }


    /**
     * Patient: see requests sent to reception.
     */
    public function patientIndex()
    {
        if (!Auth::check() || Auth::user()->role !== 'patient') {
            abort(403);
        }

        $requests = ReceptionRequest::with([
            'handler',
            'sampleRequest.bloodSample',
        ])
            ->where('patient_id', Auth::id())
            ->latest()
            ->get();

        return view(
            'reception-requests.patient_index',
            compact('requests')
        );
    }


    /**
     * Receptionist: see incoming patient contact requests.
     */
    public function receptionistIndex()
    {
        if (!Auth::check() || Auth::user()->role !== 'receptionist') {
            abort(403);
        }

        $requests = ReceptionRequest::with([
            'patient',
            'handler',
            'sampleRequest',
        ])
            ->latest()
            ->get();

        return view(
            'reception-requests.receptionist_index',
            compact('requests')
        );
    }


    /**
     * Receptionist: process a patient's contact request.
     *
     * This converts the reception request into the real
     * SampleRequest + BloodSample workflow.
     */
    public function process(
        ReceptionRequest $receptionRequest
    ): RedirectResponse {

        if (!Auth::check() || Auth::user()->role !== 'receptionist') {
            abort(403);
        }

        if ($receptionRequest->status !== 'pending') {
            return back()->with(
                'error',
                'This reception request has already been processed.'
            );
        }

        DB::transaction(function () use ($receptionRequest) {

            /*
             * Create the actual blood sample.
             */
            $bloodSample = BloodSample::create([
                'sample_code' =>
                    'BS-' . strtoupper(substr(uniqid(), -8)),

                'patient_id' =>
                    $receptionRequest->patient_id,

                'sample_type' =>
                    $receptionRequest->sample_type,

                'blood_type' =>
                    $receptionRequest->blood_type,

                'status' => 'pending',

                'collected_by' => null,
                'collected_at' => null,
                'reviewed_by' => null,
                'reviewed_at' => null,
                'rejection_reason' => null,
            ]);


            /*
             * Create the REAL sample request.
             *
             * patient_id:
             * Patient who originally contacted reception.
             *
             * requested_by:
             * Receptionist processing the request.
             */
            $sampleRequest = SampleRequest::create([
                'patient_id' =>
                    $receptionRequest->patient_id,

                'requested_by' =>
                    Auth::id(),

                'blood_sample_id' =>
                    $bloodSample->id,

                'sample_type' =>
                    $receptionRequest->sample_type,

                'blood_type' =>
                    $receptionRequest->blood_type,

                'status' => 'pending',

                'notes' =>
                    $receptionRequest->notes,
            ]);


            /*
             * Mark the reception request as handled
             * and permanently link it to the sample request.
             */
            $receptionRequest->update([
                'status' => 'handled',
                'handled_by' => Auth::id(),
                'handled_at' => now(),
                'sample_request_id' => $sampleRequest->id,
            ]);
        });


        return back()->with(
            'success',
            'Reception request processed. The sample request has been sent to admin for approval.'
        );
    }
}