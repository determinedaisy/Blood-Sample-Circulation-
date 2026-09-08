<?php

namespace App\Http\Controllers;

use App\Models\BloodSample;
use App\Models\CollectionCenter;
use App\Models\HomeCollectionRequest;
use App\Models\Laboratory;
use App\Models\SampleRequest;
use App\Models\SampleTransportation;
use App\Models\User;
use App\Notifications\PatientSampleUpdateNotification;
use App\Notifications\SampleRequestApprovedNotification;
use App\Services\LaboratoryCapacityService;
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

    public function patientIndex()
    {
        if (!Auth::check() || Auth::user()->role !== 'patient') {
            abort(403);
        }

        /*
        |--------------------------------------------------------------------------
        | PATIENT SAMPLE REQUESTS
        |--------------------------------------------------------------------------
        |
        | This page should ONLY show requests that are still waiting
        | for administrator approval.
        |
        | Once the administrator approves the request, it disappears
        | from Sample Requests.
        |
        | The resulting blood sample will appear in My Blood Samples
        | once laboratory review is completed.
        |
        */

        $requests = SampleRequest::with([
            'requester',
            'approver',
            'assignedDoctor.doctorProfile',
            'homeCollection.assignedCollector',
            'bloodSample.transportations.transporter',
            'bloodSample.transportations.collectionCenter',
            'bloodSample.transportations.laboratory',
        ])
            ->where('patient_id', Auth::id())
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view(
            'sample-requests.patient_index',
            compact('requests')
        );
    }


    public function create()
    {
        if (!Auth::check() || Auth::user()->role !== 'patient') {
            abort(403);
        }

        return view('sample-requests.create');
    }


    public function store(Request $request): RedirectResponse
    {
        if (!Auth::check() || Auth::user()->role !== 'patient') {
            abort(403);
        }

        /*
        |--------------------------------------------------------------------------
        | Validate request + collection choice
        |--------------------------------------------------------------------------
        */

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

            /*
             * Patient MUST choose the collection method
             * when the request is originally submitted.
             */
            'collection_method' => [
                'required',
                'in:center,home',
            ],

            /*
             * These are required ONLY for Home Collection.
             */
            'address' => [
                'required_if:collection_method,home',
                'nullable',
                'string',
                'max:255',
            ],

            'preferred_date' => [
                'required_if:collection_method,home',
                'nullable',
                'date',
                'after_or_equal:today',
            ],

            'preferred_time' => [
                'required_if:collection_method,home',
                'nullable',
                'string',
                'max:100',
            ],

            'instructions' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'latitude' => [
                'nullable',
                'numeric',
                'between:-90,90',
            ],

            'longitude' => [
                'nullable',
                'numeric',
                'between:-180,180',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Create Blood Sample
        |--------------------------------------------------------------------------
        */

        $bloodSample = BloodSample::create([
            'sample_code' =>
                'BS-' . strtoupper(substr(uniqid(), -8)),

            'patient_id' =>
                Auth::id(),

            'sample_type' =>
                $validated['sample_type'],

            'blood_type' =>
                $validated['blood_type'] ?? null,

            'status' =>
                'pending',

            'collected_by' =>
                null,

            'collected_at' =>
                null,

            'reviewed_by' =>
                null,

            'reviewed_at' =>
                null,

            'rejection_reason' =>
                null,
        ]);


        /*
        |--------------------------------------------------------------------------
        | Create Main Sample Request
        |--------------------------------------------------------------------------
        */

        $sampleRequest = SampleRequest::create([
            'patient_id' =>
                Auth::id(),

            'requested_by' =>
                Auth::id(),

            'blood_sample_id' =>
                $bloodSample->id,

            'sample_type' =>
                $validated['sample_type'],

            'blood_type' =>
                $validated['blood_type'] ?? null,

            'status' =>
                'pending',

            'notes' =>
                $validated['notes'] ?? null,
        ]);


        /*
        |--------------------------------------------------------------------------
        | Create Home Collection immediately if patient chose it
        |--------------------------------------------------------------------------
        |
        | We DO NOT wait until admin approval to store the patient's
        | requested home-collection details.
        |
        */

        if ($validated['collection_method'] === 'home') {

            HomeCollectionRequest::create([
                'sample_request_id' =>
                    $sampleRequest->id,

                'patient_id' =>
                    Auth::id(),

                'assigned_collector_id' =>
                    null,

                'address' =>
                    $validated['address'],

                'latitude' =>
                    $validated['latitude'] ?? null,

                'longitude' =>
                    $validated['longitude'] ?? null,

                'preferred_date' =>
                    $validated['preferred_date'],

                'preferred_time' =>
                    $validated['preferred_time'],

                'instructions' =>
                    $validated['instructions'] ?? null,

                'status' =>
                    'pending',

                'assigned_at' =>
                    null,

                'on_the_way_at' =>
                    null,

                'arrived_at' =>
                    null,

                'collected_at' =>
                    null,
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        if ($validated['collection_method'] === 'home') {

            return redirect()
                ->route('sample-requests.patient.index')
                ->with(
                    'success',
                    'Home collection request submitted successfully.'
                );
        }

        return redirect()
            ->route('sample-requests.patient.index')
            ->with(
                'success',
                'Blood sample request submitted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | TRACKING
    |--------------------------------------------------------------------------
    */

    public function tracking(
        SampleRequest $sampleRequest
    ) {
        if (!Auth::check() || Auth::user()->role !== 'patient') {
            abort(403);
        }

        if (
            (int) $sampleRequest->patient_id
            !==
            (int) Auth::id()
        ) {
            abort(403);
        }

        $sampleRequest->load([
            'requester',
            'approver',
            'assignedDoctor.doctorProfile',

            'homeCollection.assignedCollector',

            'bloodSample.transportations.transporter',
            'bloodSample.transportations.collectionCenter',
            'bloodSample.transportations.laboratory',
        ]);

        $transportation = $sampleRequest
            ->bloodSample
            ?->transportations
            ?->sortByDesc('id')
            ?->first();

        $homeCollection =
            $sampleRequest->homeCollection;

        return view(
            'sample-requests.tracking',
            compact(
                'sampleRequest',
                'transportation',
                'homeCollection'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | HOME SAMPLE COLLECTION - PATIENT
    |--------------------------------------------------------------------------
    */

    public function homeCollectionCreate(
        SampleRequest $sampleRequest
    ) {
        if (
            !Auth::check()
            || Auth::user()->role !== 'patient'
        ) {
            abort(403);
        }

        if (
            (int) $sampleRequest->patient_id
            !==
            (int) Auth::id()
        ) {
            abort(403);
        }

        if ($sampleRequest->status !== 'approved') {
            return redirect()
                ->route('sample-requests.patient.index')
                ->with(
                    'error',
                    'Your sample request must be approved before requesting home collection.'
                );
        }

        if (!$sampleRequest->blood_sample_id) {
            return redirect()
                ->route('sample-requests.patient.index')
                ->with(
                    'error',
                    'No blood sample is connected to this request.'
                );
        }

        if (
            $sampleRequest
                ->homeCollection()
                ->exists()
        ) {
            return redirect()
                ->route('sample-requests.patient.index')
                ->with(
                    'error',
                    'A home collection request already exists for this sample.'
                );
        }

        $transportationExists =
            SampleTransportation::where(
                'blood_sample_id',
                $sampleRequest->blood_sample_id
            )->exists();

        if ($transportationExists) {
            return redirect()
                ->route('sample-requests.patient.index')
                ->with(
                    'error',
                    'Collection or transportation has already been arranged for this sample.'
                );
        }

        return view(
            'sample-requests.home_collection_create',
            compact('sampleRequest')
        );
    }


    public function homeCollectionStore(
        Request $request,
        SampleRequest $sampleRequest
    ): RedirectResponse {

        if (
            !Auth::check()
            || Auth::user()->role !== 'patient'
        ) {
            abort(403);
        }

        if (
            (int) $sampleRequest->patient_id
            !==
            (int) Auth::id()
        ) {
            abort(403);
        }

        if ($sampleRequest->status !== 'approved') {
            return redirect()
                ->route('sample-requests.patient.index')
                ->with(
                    'error',
                    'Your sample request must be approved before requesting home collection.'
                );
        }

        if (!$sampleRequest->blood_sample_id) {
            return redirect()
                ->route('sample-requests.patient.index')
                ->with(
                    'error',
                    'No blood sample is connected to this request.'
                );
        }

        if (
            $sampleRequest
                ->homeCollection()
                ->exists()
        ) {
            return redirect()
                ->route('sample-requests.patient.index')
                ->with(
                    'error',
                    'A home collection request already exists for this sample.'
                );
        }

        $transportationExists =
            SampleTransportation::where(
                'blood_sample_id',
                $sampleRequest->blood_sample_id
            )->exists();

        if ($transportationExists) {
            return redirect()
                ->route('sample-requests.patient.index')
                ->with(
                    'error',
                    'Collection or transportation has already been arranged for this sample.'
                );
        }

        $validated = $request->validate([
            'address' => [
                'required',
                'string',
                'max:255',
            ],

            'latitude' => [
                'nullable',
                'numeric',
                'between:-90,90',
            ],

            'longitude' => [
                'nullable',
                'numeric',
                'between:-180,180',
            ],

            'preferred_date' => [
                'required',
                'date',
                'after_or_equal:today',
            ],

            'preferred_time' => [
                'required',
                'string',
                'max:100',
            ],

            'instructions' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        HomeCollectionRequest::create([
            'sample_request_id' =>
                $sampleRequest->id,

            'patient_id' =>
                Auth::id(),

            'assigned_collector_id' =>
                null,

            'address' =>
                $validated['address'],

            'latitude' =>
                $validated['latitude'] ?? null,

            'longitude' =>
                $validated['longitude'] ?? null,

            'preferred_date' =>
                $validated['preferred_date'],

            'preferred_time' =>
                $validated['preferred_time'],

            'instructions' =>
                $validated['instructions'] ?? null,

            'status' =>
                'pending',

            'assigned_at' =>
                null,

            'on_the_way_at' =>
                null,

            'arrived_at' =>
                null,

            'collected_at' =>
                null,
        ]);

        return redirect()
            ->route('sample-requests.patient.index')
            ->with(
                'success',
                'Home collection requested successfully. An administrator will assign a collector.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | RECEPTIONIST
    |--------------------------------------------------------------------------
    */

    public function receptionistIndex()
    {
        if (
            !Auth::check()
            || Auth::user()->role !== 'receptionist'
        ) {
            abort(403);
        }

        $requests = SampleRequest::with([
            'patient',
            'bloodSample.transportations.transporter',
            'bloodSample.transportations.laboratory',
        ])
            ->where(
                'requested_by',
                Auth::id()
            )
            ->latest()
            ->get();

        return view(
            'sample-requests.receptionist_index',
            compact('requests')
        );
    }


    public function receptionistCreate()
    {
        if (
            !Auth::check()
            || Auth::user()->role !== 'receptionist'
        ) {
            abort(403);
        }

        $patients = User::where(
            'role',
            'patient'
        )
            ->orderBy('name')
            ->get();

        return view(
            'sample-requests.receptionist_create',
            compact('patients')
        );
    }


    public function receptionistStore(
        Request $request
    ): RedirectResponse {

        if (
            !Auth::check()
            || Auth::user()->role !== 'receptionist'
        ) {
            abort(403);
        }

        $validated = $request->validate([
            'patient_id' => [
                'required',
                'exists:users,id',
            ],

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

        $patient = User::where(
            'id',
            $validated['patient_id']
        )
            ->where(
                'role',
                'patient'
            )
            ->firstOrFail();

        $bloodSample = BloodSample::create([
            'sample_code' =>
                'BS-' . strtoupper(substr(uniqid(), -8)),

            'patient_id' =>
                $patient->id,

            'sample_type' =>
                $validated['sample_type'],

            'blood_type' =>
                $validated['blood_type'] ?? null,

            'status' =>
                'pending',

            'collected_by' =>
                null,

            'collected_at' =>
                null,

            'reviewed_by' =>
                null,

            'reviewed_at' =>
                null,

            'rejection_reason' =>
                null,
        ]);

        SampleRequest::create([
            'patient_id' =>
                $patient->id,

            'requested_by' =>
                Auth::id(),

            'blood_sample_id' =>
                $bloodSample->id,

            'sample_type' =>
                $validated['sample_type'],

            'blood_type' =>
                $validated['blood_type'] ?? null,

            'status' =>
                'pending',

            'notes' =>
                $validated['notes'] ?? null,
        ]);

        return redirect()
            ->route(
                'sample-requests.receptionist.index'
            )
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

    public function adminIndex()
    {
        if (
            !Auth::check()
            || Auth::user()->role !== 'admin'
        ) {
            abort(403);
        }

        $requests = SampleRequest::with([
            'patient',
            'requester',
            'approver',

            'assignedDoctor.doctorProfile',

            'bloodSample.transportations.transporter',
            'bloodSample.transportations.collectionCenter',
            'bloodSample.transportations.laboratory',
        ])
            ->whereDoesntHave('homeCollection')
            ->latest()
            ->get();

        $collectors = User::where(
            'role',
            'sample_collector'
        )
            ->orderBy('name')
            ->get();

        $collectionCenters =
            CollectionCenter::where(
                'is_active',
                true
            )
                ->orderBy('name')
                ->get();

        $laboratories =
            Laboratory::where(
                'is_active',
                true
            )
                ->orderBy('name')
                ->get();

        $availableDoctors =
            User::where(
                'role',
                'doctor'
            )
                ->with('doctorProfile')
                ->orderBy('name')
                ->get();

        return view(
            'sample-requests.admin_index',
            compact(
                'requests',
                'collectors',
                'collectionCenters',
                'laboratories',
                'availableDoctors'
            )
        );
    }


    public function approve(
        SampleRequest $sampleRequest
    ): RedirectResponse {

        if (
            !Auth::check()
            || Auth::user()->role !== 'admin'
        ) {
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
            'status' =>
                'approved',

            'approved_by' =>
                Auth::id(),

            'approved_at' =>
                now(),
        ]);

        $sampleRequest->loadMissing([
            'patient',
            'bloodSample',
        ]);

        $sampleRequest->patient?->notify(
            new SampleRequestApprovedNotification($sampleRequest)
        );

        return back()->with(
            'success',
            'Sample request approved successfully. The patient has been notified.'
        );
    }


    public function decline(
        SampleRequest $sampleRequest
    ): RedirectResponse {

        if (
            !Auth::check()
            || Auth::user()->role !== 'admin'
        ) {
            abort(403);
        }

        if ($sampleRequest->status !== 'pending') {
            return back()->with(
                'error',
                'Only pending requests can be declined.'
            );
        }

        $sampleRequest->loadMissing([
            'patient',
            'bloodSample',
        ]);

        $sampleCode =
            $sampleRequest
                ->bloodSample
                ?->sample_code;

        $sampleRequest->update([
            'status' =>
                'declined',

            'approved_by' =>
                Auth::id(),

            'approved_at' =>
                now(),
        ]);

        $sampleRequest->patient?->notify(
            new PatientSampleUpdateNotification(
                kind: 'sample_request_declined',
                title: 'Sample request declined',
                message:
                    'Your '
                    .$sampleRequest->sample_type
                    .' sample request was declined by the administrator.',
                sampleRequestId:
                    $sampleRequest->id,
                sampleCode:
                    $sampleCode,
                actionLabel:
                    'View My Requests',
            )
        );

        if ($sampleRequest->bloodSample) {

            $sampleRequest
                ->bloodSample
                ->delete();
        }

        return back()->with(
            'success',
            'Sample request declined. The patient has been notified.'
        );
    }


    public function assignCollector(
        Request $request,
        SampleRequest $sampleRequest,
        LaboratoryCapacityService $capacityService
    ): RedirectResponse {

        if (
            !Auth::check()
            || Auth::user()->role !== 'admin'
        ) {
            abort(403);
        }

        if ($sampleRequest->status !== 'approved') {
            return back()->with(
                'error',
                'The sample request must be approved before assigning a collector.'
            );
        }

        if (
            $sampleRequest
                ->homeCollection()
                ->exists()
        ) {
            return back()->with(
                'error',
                'This request uses Home Collection. Assign its collector from the Home Collections portal.'
            );
        }

        if (!$sampleRequest->blood_sample_id) {
            return back()->with(
                'error',
                'No blood sample is connected to this request.'
            );
        }

        // STRICT SECURITY CHECK: Ensure payment is completed
        $payment =
            $sampleRequest
                ->bloodSample
                ?->payment;

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

            'scheduled_test_date' => [
                'required',
                'date',
                'after_or_equal:today',
            ],
        ]);

        $collector = User::where(
            'id',
            $validated['transported_by']
        )
            ->where(
                'role',
                'sample_collector'
            )
            ->first();

        if (!$collector) {
            return back()->with(
                'error',
                'The selected user is not a sample collector.'
            );
        }

        $alreadyAssigned =
            SampleTransportation::where(
                'blood_sample_id',
                $sampleRequest->blood_sample_id
            )->exists();

        if ($alreadyAssigned) {
            return back()->with(
                'error',
                'A collector has already been assigned to this blood sample.'
            );
        }

        $capacityService->schedule([
            'blood_sample_id' =>
                $sampleRequest->blood_sample_id,

            'collection_center_id' =>
                $validated['collection_center_id'],

            'laboratory_id' =>
                $validated['laboratory_id'],

            'transported_by' =>
                $validated['transported_by'],

            'status' =>
                'pending',
        ], $validated['scheduled_test_date']);

        return back()->with(
            'success',
            'Collector assigned and laboratory testing slot reserved successfully.'
        );
    }


    public function assignDoctor(
        Request $request,
        SampleRequest $sampleRequest
    ): RedirectResponse {

        if (
            !Auth::check()
            || Auth::user()->role !== 'admin'
        ) {
            abort(403);
        }

        if ($sampleRequest->status !== 'approved') {
            return back()->with(
                'error',
                'Only approved sample requests can be assigned to a doctor.'
            );
        }

        if (!$sampleRequest->blood_sample_id) {
            return back()->with(
                'error',
                'No blood sample is connected to this request.'
            );
        }

        $transportation =
            SampleTransportation::where(
                'blood_sample_id',
                $sampleRequest->blood_sample_id
            )
                ->latest('id')
                ->first();

        if (!$transportation) {
            return back()->with(
                'error',
                'No transportation record exists for this sample.'
            );
        }

        if (
            $transportation->status
            !==
            'delivered'
        ) {
            return back()->with(
                'error',
                'The sample must be delivered before a doctor can be assigned.'
            );
        }

        if ($sampleRequest->assigned_doctor_id) {
            return back()->with(
                'error',
                'A doctor has already been assigned to this request.'
            );
        }

        $validated = $request->validate([
            'assigned_doctor_id' => [
                'required',
                'exists:users,id',
            ],
        ]);

        $doctor = User::where(
            'id',
            $validated['assigned_doctor_id']
        )
            ->where(
                'role',
                'doctor'
            )
            ->first();

        if (!$doctor) {
            return back()->with(
                'error',
                'The selected user is not a doctor.'
            );
        }

        $sampleRequest->update([
            'assigned_doctor_id' =>
                $doctor->id,
        ]);

        return back()->with(
            'success',
            'Doctor assigned successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DOCTOR
    |--------------------------------------------------------------------------
    */

    public function doctorIndex()
    {
        if (
            !Auth::check()
            || Auth::user()->role !== 'doctor'
        ) {
            abort(403);
        }

        $requests = SampleRequest::with([
            'patient',
            'requester',
            'approver',
            'bloodSample',
            'bloodSample.sampleReport',
            'bloodSample.transportations.transporter',
            'bloodSample.transportations.collectionCenter',
            'bloodSample.transportations.laboratory',
        ])
            ->where(
                'assigned_doctor_id',
                Auth::id()
            )
            ->latest()
            ->get();

        return view(
            'sample-requests.doctor_index',
            compact('requests')
        );
    }


    public function doctorShow(
        SampleRequest $sampleRequest
    ) {
        if (
            !Auth::check()
            || Auth::user()->role !== 'doctor'
        ) {
            abort(403);
        }

        if (
            (int) $sampleRequest->assigned_doctor_id
            !==
            (int) Auth::id()
        ) {
            abort(403);
        }

        $sampleRequest->load([
            'patient',
            'requester',
            'approver',
            'assignedDoctor.doctorProfile',
            'bloodSample',
            'bloodSample.sampleReport.results',
            'bloodSample.sampleReport.doctor',
            'bloodSample.transportations.transporter',
            'bloodSample.transportations.collectionCenter',
            'bloodSample.transportations.laboratory',
        ]);

        $transportation = $sampleRequest
            ->bloodSample
            ?->transportations
            ?->sortByDesc('id')
            ?->first();

        return view(
            'sample-requests.doctor_show',
            compact(
                'sampleRequest',
                'transportation'
            )
        );
    }
}