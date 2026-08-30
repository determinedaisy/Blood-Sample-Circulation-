<?php

namespace App\Http\Controllers;

use App\Models\BloodSample;
use App\Models\CollectionCenter;
use App\Models\Laboratory;
use App\Models\SampleRequest;
use App\Models\SampleTransportation;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SampleRequestController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | PATIENT
    |--------------------------------------------------------------------------
    */

    /**
     * Patient: show own sample requests.
     */
    public function patientIndex()
    {
        if (!Auth::check() || Auth::user()->role !== 'patient') {
            abort(403);
        }

        $requests = SampleRequest::with([
            'requester',
            'bloodSample.transportations.transporter',
            'approver',
        ])
            ->where('patient_id', Auth::id())
            ->latest()
            ->get();

        return view(
            'sample-requests.patient_index',
            compact('requests')
        );
    }


    /**
     * Patient: show request form.
     */
    public function create()
    {
        if (!Auth::check() || Auth::user()->role !== 'patient') {
            abort(403);
        }

        return view('sample-requests.create');
    }


    /**
     * Patient: submit own sample request.
     */
    public function store(Request $request): RedirectResponse
    {
        if (!Auth::check() || Auth::user()->role !== 'patient') {
            abort(403);
        }

        $validated = $request->validate([
            'sample_type' => ['required', 'string', 'max:100'],
            'blood_type' => ['nullable', 'string', 'max:10'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        /*
         * Create pending blood sample immediately.
         */
        $bloodSample = BloodSample::create([
            'sample_code' => 'BS-' . strtoupper(substr(uniqid(), -8)),
            'patient_id' => Auth::id(),
            'sample_type' => $validated['sample_type'],
            'blood_type' => $validated['blood_type'] ?? null,
            'status' => 'pending',

            'collected_by' => null,
            'collected_at' => null,
            'reviewed_by' => null,
            'reviewed_at' => null,
            'rejection_reason' => null,
        ]);

        /*
         * Create request and connect it to blood sample.
         */
        SampleRequest::create([
            'patient_id' => Auth::id(),
            'requested_by' => Auth::id(),
            'blood_sample_id' => $bloodSample->id,
            'sample_type' => $validated['sample_type'],
            'blood_type' => $validated['blood_type'] ?? null,
            'status' => 'pending',
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()
            ->route('sample-requests.patient.index')
            ->with(
                'success',
                'Blood sample request submitted successfully.'
            );
    }


    /**
     * Patient: track complete request/sample/transport lifecycle.
     */
    public function tracking(SampleRequest $sampleRequest)
    {
        if (!Auth::check() || Auth::user()->role !== 'patient') {
            abort(403);
        }

        /*
         * Patient can only track their own request.
         */
        if ((int) $sampleRequest->patient_id !== (int) Auth::id()) {
            abort(403);
        }

        $sampleRequest->load([
            'requester',
            'approver',
            'bloodSample.transportations.transporter',
            'bloodSample.transportations.collectionCenter',
            'bloodSample.transportations.laboratory',
        ]);

        $transportation = $sampleRequest
            ->bloodSample
            ?->transportations
            ?->first();

        return view(
            'sample-requests.tracking',
            compact(
                'sampleRequest',
                'transportation'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | RECEPTIONIST
    |--------------------------------------------------------------------------
    */

    /**
     * Receptionist: show all requests created by them.
     */
    public function receptionistIndex()
    {
        if (!Auth::check() || Auth::user()->role !== 'receptionist') {
            abort(403);
        }

        $requests = SampleRequest::with([
            'patient',
            'bloodSample.transportations.transporter',
            'bloodSample.transportations.laboratory',
        ])
            ->where('requested_by', Auth::id())
            ->latest()
            ->get();

        return view(
            'sample-requests.receptionist_index',
            compact('requests')
        );
    }


    /**
     * Receptionist: show form to create request for patient.
     */
    public function receptionistCreate()
    {
        if (!Auth::check() || Auth::user()->role !== 'receptionist') {
            abort(403);
        }

        $patients = User::where('role', 'patient')
            ->orderBy('name')
            ->get();

        return view(
            'sample-requests.receptionist_create',
            compact('patients')
        );
    }


    /**
     * Receptionist: create request for selected patient.
     */
    public function receptionistStore(
        Request $request
    ): RedirectResponse {

        if (!Auth::check() || Auth::user()->role !== 'receptionist') {
            abort(403);
        }

        $validated = $request->validate([
            'patient_id' => ['required', 'exists:users,id'],
            'sample_type' => ['required', 'string', 'max:100'],
            'blood_type' => ['nullable', 'string', 'max:10'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        /*
         * Make sure selected user really is a patient.
         */
        $patient = User::where('id', $validated['patient_id'])
            ->where('role', 'patient')
            ->firstOrFail();

        /*
         * Create pending blood sample for selected patient.
         */
        $bloodSample = BloodSample::create([
            'sample_code' => 'BS-' . strtoupper(substr(uniqid(), -8)),
            'patient_id' => $patient->id,
            'sample_type' => $validated['sample_type'],
            'blood_type' => $validated['blood_type'] ?? null,
            'status' => 'pending',

            'collected_by' => null,
            'collected_at' => null,
            'reviewed_by' => null,
            'reviewed_at' => null,
            'rejection_reason' => null,
        ]);

        /*
         * Create request.
         *
         * patient_id   = who the request belongs to
         * requested_by = receptionist who created it
         */
        SampleRequest::create([
            'patient_id' => $patient->id,
            'requested_by' => Auth::id(),
            'blood_sample_id' => $bloodSample->id,
            'sample_type' => $validated['sample_type'],
            'blood_type' => $validated['blood_type'] ?? null,
            'status' => 'pending',
            'notes' => $validated['notes'] ?? null,
        ]);

        /*
         * Go to receptionist request history.
         */
        return redirect()
            ->route('sample-requests.receptionist.index')
            ->with(
                'success',
                'Blood sample request created for the patient.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | ADMIN
    |--------------------------------------------------------------------------
    */

    /**
     * Admin: show all sample requests.
     */
    public function adminIndex()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403);
        }

        $requests = SampleRequest::with([
            'patient',
            'requester',
            'approver',
            'bloodSample.transportations.transporter',
            'bloodSample.transportations.collectionCenter',
            'bloodSample.transportations.laboratory',
        ])
            ->latest()
            ->get();

        $collectors = User::where('role', 'sample_collector')
            ->orderBy('name')
            ->get();

        $collectionCenters = CollectionCenter::where(
            'is_active',
            true
        )
            ->orderBy('name')
            ->get();

        $laboratories = Laboratory::where(
            'is_active',
            true
        )
            ->orderBy('name')
            ->get();

        return view(
            'sample-requests.admin_index',
            compact(
                'requests',
                'collectors',
                'collectionCenters',
                'laboratories'
            )
        );
    }

    


    /**
     * Admin: approve request.
     */
    public function approve(
        SampleRequest $sampleRequest
    ): RedirectResponse {

        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403);
        }

        if ($sampleRequest->status !== 'pending') {
            return back()->with(
                'error',
                'Only pending requests can be approved.'
            );
        }

        // STRICT SECURITY CHECK: Ensure payment is completed
        $payment = $sampleRequest->bloodSample?->payment;
        if (!$payment || $payment->status !== 'completed') {
            return back()->with(
                'error',
                'Action Denied: Cannot approve this request until the patient has completed the bKash payment.'
            );
        }

        $sampleRequest->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return back()->with(
            'success',
            'Sample request approved successfully. You can now assign a collector.'
        );
    }


    /**
     * Admin: decline request.
     */
    public function decline(
        SampleRequest $sampleRequest
    ): RedirectResponse {

        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403);
        }

        if ($sampleRequest->status !== 'pending') {
            return back()->with(
                'error',
                'Only pending requests can be declined.'
            );
        }

        $sampleRequest->update([
            'status' => 'declined',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        /*
         * A declined request never reaches transportation.
         * Delete its pending blood sample.
         */
        if ($sampleRequest->bloodSample) {
            $sampleRequest->bloodSample->delete();
        }

        return back()->with(
            'success',
            'Sample request declined.'
        );
    }


    /**
     * Admin: assign collector after approval.
     */
    public function assignCollector(
        Request $request,
        SampleRequest $sampleRequest
    ): RedirectResponse {

        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403);
        }

        if ($sampleRequest->status !== 'approved') {
            return back()->with(
                'error',
                'The sample request must be approved before assigning a collector.'
            );
        }

        if (!$sampleRequest->blood_sample_id) {
            return back()->with(
                'error',
                'No blood sample is connected to this request.'
            );
        }

        // STRICT SECURITY CHECK: Ensure payment is completed
        $payment = $sampleRequest->bloodSample?->payment;
        if (!$payment || $payment->status !== 'completed') {
            return back()->with(
                'error',
                'Action Denied: Cannot assign a collector until the lab fee is paid in full.'
            );
        }

        $validated = $request->validate([
            'transported_by' => [
                'required',
                'exists:users,id',
            ],

            'collection_center_id' => [
                'required',
                'exists:collection_centers,id',
            ],

            'laboratory_id' => [
                'required',
                'exists:laboratories,id',
            ],
        ]);

        /*
         * Selected user must actually be collector.
         */
        $collector = User::where(
            'id',
            $validated['transported_by']
        )
            ->where('role', 'sample_collector')
            ->first();

        if (!$collector) {
            return back()->with(
                'error',
                'The selected user is not a sample collector.'
            );
        }

        /*
         * Prevent assigning same sample twice.
         */
        $alreadyAssigned = SampleTransportation::where(
            'blood_sample_id',
            $sampleRequest->blood_sample_id
        )->exists();

        if ($alreadyAssigned) {
            return back()->with(
                'error',
                'A collector has already been assigned to this blood sample.'
            );
        }

        SampleTransportation::create([
            'blood_sample_id' => $sampleRequest->blood_sample_id,
            'collection_center_id' => $validated['collection_center_id'],
            'laboratory_id' => $validated['laboratory_id'],
            'transported_by' => $validated['transported_by'],
            'status' => 'pending',
        ]);

        return back()->with(
            'success',
            'Collector assigned successfully.'
        );
    }
}