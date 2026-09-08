<?php

namespace App\Http\Controllers;

use App\Models\HomeCollectionRequest;
use App\Models\Laboratory;
use App\Models\SampleTransportation;
use App\Models\User;
use App\Notifications\PatientSampleUpdateNotification;
use App\Notifications\HomeCollectionAssignedNotification;
use App\Notifications\LabStaffSampleAssignedNotification;
use App\Notifications\SampleRequestDeclinedNotification;
use App\Services\LaboratoryCapacityService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class HomeCollectionController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | ADMIN - HOME COLLECTION LIST
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

        $homeCollections = HomeCollectionRequest::with([
            'patient',
            'assignedCollector',
            'sampleRequest',
            'sampleRequest.bloodSample',
            'sampleRequest.bloodSample.assignedLabStaff',
            'sampleRequest.bloodSample.transportations.transporter',
            'sampleRequest.bloodSample.transportations.collectionCenter',
            'sampleRequest.bloodSample.transportations.laboratory',
        ])
            ->orderByDesc('created_at')
            ->get();

        $collectors = User::where(
            'role',
            'sample_collector'
        )
            ->orderBy('name')
            ->get();

        $labStaff = User::where(
            'role',
            'lab_staff'
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
            'home-collections.admin_index',
            compact(
                'homeCollections',
                'collectors',
                'labStaff',
                'laboratories'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN - APPROVE HOME COLLECTION REQUEST
    |--------------------------------------------------------------------------
    */

    public function approve(
        Request $request,
        HomeCollectionRequest $homeCollection
    ): RedirectResponse|JsonResponse {

        if (
            !Auth::check()
            || Auth::user()->role !== 'admin'
        ) {
            abort(403);
        }

        $homeCollection->load('sampleRequest');

        if (!$homeCollection->sampleRequest) {

            if ($request->expectsJson()) {
                return response()->json([
                    'message' =>
                        'No sample request is connected to this home collection.',
                ], 422);
            }

            return back()->with(
                'error',
                'No sample request is connected to this home collection.'
            );
        }

        if ($homeCollection->sampleRequest->status !== 'pending') {

            if ($request->expectsJson()) {
                return response()->json([
                    'message' =>
                        'This sample request has already been processed.',
                ], 422);
            }

            return back()->with(
                'error',
                'This sample request has already been processed.'
            );
        }

        $homeCollection->sampleRequest->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' =>
                    'Sample request approved successfully. You can now assign a Sample Collector.',
            ]);
        }

        return back()->with(
            'success',
            'Sample request approved successfully. You can now assign a Sample Collector.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN - ASSIGN SAMPLE COLLECTOR
    |--------------------------------------------------------------------------
    */

    public function assignCollector(
        Request $request,
        HomeCollectionRequest $homeCollection
    ): RedirectResponse|JsonResponse {

        if (
            !Auth::check()
            || Auth::user()->role !== 'admin'
        ) {
            abort(403);
        }

        $homeCollection->load('sampleRequest');

        if (!$homeCollection->sampleRequest) {

            if ($request->expectsJson()) {
                return response()->json([
                    'message' =>
                        'No sample request is connected to this home collection.',
                ], 422);
            }

            return back()->with(
                'error',
                'No sample request is connected to this home collection.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Sample request must be approved
        |--------------------------------------------------------------------------
        */

        if ($homeCollection->sampleRequest->status !== 'approved') {

            if ($request->expectsJson()) {
                return response()->json([
                    'message' =>
                        'The sample request must be approved before assigning a Sample Collector.',
                ], 422);
            }

            return back()->with(
                'error',
                'The sample request must be approved before assigning a Sample Collector.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Home collection must still be assignable
        |--------------------------------------------------------------------------
        */

        if (
            !in_array(
                $homeCollection->status,
                ['pending', 'approved', 'assigned'],
                true
            )
        ) {

            if ($request->expectsJson()) {
                return response()->json([
                    'message' =>
                        'Action cannot be done for this home collection in its current status.',
                ], 422);
            }

            return back()->with(
                'error',
                'Action cannot be done for this home collection in its current status.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Validate Sample Collector
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'assigned_collector_id' => [
                'required',
                'exists:users,id',
            ],
        ]);

        $collector = User::where(
            'id',
            $validated['assigned_collector_id']
        )
            ->where(
                'role',
                'sample_collector'
            )
            ->first();

        if (!$collector) {

            if ($request->expectsJson()) {
                return response()->json([
                    'message' =>
                        'The selected user is not a Sample Collector.',
                ], 422);
            }

            return back()->with(
                'error',
                'The selected user is not a Sample Collector.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Assign Collector
        |--------------------------------------------------------------------------
        */

        $homeCollection->update([
            'assigned_collector_id' => $collector->id,
            'status' => 'assigned',
            'assigned_at' => now(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Notify Collector
        |--------------------------------------------------------------------------
        */

        $homeCollection->loadMissing([
            'patient',
            'sampleRequest.bloodSample',
        ]);

        $collector->notify(
            new HomeCollectionAssignedNotification(
                $homeCollection
            )
        );

        if ($request->expectsJson()) {
            return response()->json([
                'message' =>
                    'Sample Collector '.$collector->name.' assigned successfully.',
            ]);
        }

        return back()->with(
            'success',
            'Sample Collector '.$collector->name.' assigned successfully.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN - DECLINE HOME COLLECTION REQUEST
    |--------------------------------------------------------------------------
    */

    public function decline(
        Request $request,
        HomeCollectionRequest $homeCollection
    ): RedirectResponse|JsonResponse {

        if (
            !Auth::check()
            || Auth::user()->role !== 'admin'
        ) {
            abort(403);
        }

        $homeCollection->load([
            'patient',
            'sampleRequest',
        ]);

        if (!$homeCollection->sampleRequest) {

            if ($request->expectsJson()) {
                return response()->json([
                    'message' =>
                        'No sample request is connected to this home collection.',
                ], 422);
            }

            return back()->with(
                'error',
                'No sample request is connected to this home collection.'
            );
        }

        if ($homeCollection->sampleRequest->status !== 'pending') {

            if ($request->expectsJson()) {
                return response()->json([
                    'message' =>
                        'This sample request has already been processed.',
                ], 422);
            }

            return back()->with(
                'error',
                'This sample request has already been processed.'
            );
        }

        $validated = $request->validate([
            'decline_reason' => [
                'required',
                'string',
                'min:3',
                'max:1000',
            ],
        ]);

        $sampleRequest = $homeCollection->sampleRequest;

        $sampleRequest->status = 'declined';
        $sampleRequest->decline_reason =
            $validated['decline_reason'];

        $sampleRequest->save();

        $sampleRequest->load('bloodSample');

        if ($homeCollection->patient) {
            $homeCollection->patient->notify(
                new SampleRequestDeclinedNotification(
                    $sampleRequest
                )
            );
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' =>
                    'Sample request declined successfully. The patient has been notified.',
            ]);
        }

        return back()->with(
            'success',
            'Sample request declined successfully. The patient has been notified.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN - UPDATE ROUTE ORDER
    |--------------------------------------------------------------------------
    */

    public function updateRouteOrder(
        Request $request
    ): JsonResponse {

        if (
            !Auth::check()
            || Auth::user()->role !== 'admin'
        ) {
            abort(403);
        }

        $validated = $request->validate([
            'route_date' => [
                'required',
                'date',
            ],

            'collector_id' => [
                'required',
                'integer',
                'exists:users,id',
            ],

            'ordered_ids' => [
                'required',
                'array',
                'min:1',
            ],

            'ordered_ids.*' => [
                'required',
                'integer',
                'distinct',
                'exists:home_collection_requests,id',
            ],
        ]);

        $collector = User::where(
            'id',
            $validated['collector_id']
        )
            ->where(
                'role',
                'sample_collector'
            )
            ->first();

        if (!$collector) {
            throw ValidationException::withMessages([
                'collector_id' =>
                    'The selected user is not a Sample Collector.',
            ]);
        }

        $orderedIds = collect(
            $validated['ordered_ids']
        )
            ->map(
                fn ($id) => (int) $id
            )
            ->values();

        $matchingCount = HomeCollectionRequest::query()
            ->whereIn(
                'id',
                $orderedIds
            )
            ->where(
                'assigned_collector_id',
                $collector->id
            )
            ->whereDate(
                'preferred_date',
                $validated['route_date']
            )
            ->count();

        if (
            $matchingCount
            !== $orderedIds->count()
        ) {
            throw ValidationException::withMessages([
                'ordered_ids' =>
                    'Every stop must belong to the selected collector and route date.',
            ]);
        }

        DB::transaction(
            function () use (
                $validated,
                $collector,
                $orderedIds
            ): void {

                DB::table(
                    'home_collection_requests'
                )
                    ->where(
                        'assigned_collector_id',
                        $collector->id
                    )
                    ->whereDate(
                        'preferred_date',
                        $validated['route_date']
                    )
                    ->update([
                        'route_order' => null,
                    ]);

                foreach (
                    $orderedIds as $index => $homeCollectionId
                ) {
                    DB::table(
                        'home_collection_requests'
                    )
                        ->where(
                            'id',
                            $homeCollectionId
                        )
                        ->update([
                            'route_order' => $index + 1,
                        ]);
                }
            }
        );

        return response()->json([
            'message' =>
                'Daily collection route saved successfully.',

            'stop_count' =>
                $orderedIds->count(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN - OLD LABORATORY TRANSPORTATION
    |--------------------------------------------------------------------------
    */

    public function sendToLaboratory(
        Request $request,
        HomeCollectionRequest $homeCollection,
        LaboratoryCapacityService $capacityService
    ): RedirectResponse {

        if (
            !Auth::check()
            || Auth::user()->role !== 'admin'
        ) {
            abort(403);
        }

        $homeCollection->load('sampleRequest');

        if (
            !$homeCollection->sampleRequest
            || $homeCollection->sampleRequest->status !== 'approved'
        ) {
            return back()->with(
                'error',
                'The linked sample request must be approved before laboratory transportation can be arranged.'
            );
        }

        if ($homeCollection->status !== 'collected') {
            return back()->with(
                'error',
                'The home sample has not been collected yet.'
            );
        }

        $validated = $request->validate([
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

        $laboratory = Laboratory::where(
            'id',
            $validated['laboratory_id']
        )
            ->where(
                'is_active',
                true
            )
            ->first();

        if (!$laboratory) {
            return back()->with(
                'error',
                'The selected laboratory is not active.'
            );
        }

        $homeCollection->load(
            'sampleRequest.bloodSample'
        );

        $bloodSample =
            $homeCollection
                ->sampleRequest
                ?->bloodSample;

        if (!$bloodSample) {
            return back()->with(
                'error',
                'No blood sample is connected to this home collection.'
            );
        }

        $alreadyExists = SampleTransportation::where(
            'blood_sample_id',
            $bloodSample->id
        )->exists();

        if ($alreadyExists) {
            return back()->with(
                'error',
                'Transportation has already been arranged for this blood sample.'
            );
        }

        if (!$homeCollection->assigned_collector_id) {
            return back()->with(
                'error',
                'This home collection does not have an assigned collector.'
            );
        }

        $capacityService->schedule([
            'blood_sample_id' =>
                $bloodSample->id,

            'collection_center_id' =>
                null,

            'laboratory_id' =>
                $laboratory->id,

            'transported_by' =>
                $homeCollection->assigned_collector_id,

            'status' =>
                'pending',

            'departure_time' =>
                null,

            'arrival_time' =>
                null,

            'notes' =>
                'Home collection sample - transport from patient home to laboratory.',
        ], $validated['scheduled_test_date']);

        return back()->with(
            'success',
            'Legacy laboratory transportation record created successfully.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN - ASSIGN LAB STAFF
    |--------------------------------------------------------------------------
    */

    public function assignLabStaff(
        Request $request,
        HomeCollectionRequest $homeCollection
    ): RedirectResponse|JsonResponse {

        if (
            !Auth::check()
            || Auth::user()->role !== 'admin'
        ) {
            abort(403);
        }

        $homeCollection->load(
            'sampleRequest.bloodSample'
        );

        if ($homeCollection->status !== 'collected') {

            if ($request->expectsJson()) {
                return response()->json([
                    'message' =>
                        'Lab Staff can only be assigned after the Sample Collector has collected the sample.',
                ], 422);
            }

            return back()->with(
                'error',
                'Lab Staff can only be assigned after the Sample Collector has collected the sample.'
            );
        }

        $bloodSample =
            $homeCollection
                ->sampleRequest
                ?->bloodSample;

        if (!$bloodSample) {

            if ($request->expectsJson()) {
                return response()->json([
                    'message' =>
                        'No Blood Sample is connected to this home collection request.',
                ], 422);
            }

            return back()->with(
                'error',
                'No Blood Sample is connected to this home collection request.'
            );
        }

        if (
            $bloodSample->status === 'accepted'
            || $bloodSample->status === 'rejected'
        ) {

            if ($request->expectsJson()) {
                return response()->json([
                    'message' =>
                        'This Blood Sample has already been examined.',
                ], 422);
            }

            return back()->with(
                'error',
                'This Blood Sample has already been examined.'
            );
        }

        $validated = $request->validate([
            'assigned_lab_staff_id' => [
                'required',
                'exists:users,id',
            ],
        ]);

        $labStaff = User::where(
            'id',
            $validated['assigned_lab_staff_id']
        )
            ->where(
                'role',
                'lab_staff'
            )
            ->first();

        if (!$labStaff) {

            if ($request->expectsJson()) {
                return response()->json([
                    'message' =>
                        'The selected user is not a Lab Staff member.',
                ], 422);
            }

            return back()->with(
                'error',
                'The selected user is not a Lab Staff member.'
            );
        }

        $bloodSample->update([
            'assigned_lab_staff_id' =>
                $labStaff->id,
        ]);

        $labStaff->notify(
            new LabStaffSampleAssignedNotification(
                $bloodSample
            )
        );

        if ($request->expectsJson()) {
            return response()->json([
                'message' =>
                    'Lab Staff '.$labStaff->name.' assigned successfully. The sample is now waiting for examination.',
            ]);
        }

        return back()->with(
            'success',
            'Lab Staff '.$labStaff->name.' assigned successfully. The sample is now waiting for examination.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | COLLECTOR - ASSIGNED HOME COLLECTIONS
    |--------------------------------------------------------------------------
    */

    public function collectorIndex()
    {
        if (
            !Auth::check()
            || Auth::user()->role !== 'sample_collector'
        ) {
            abort(403);
        }

        $homeCollections = HomeCollectionRequest::with([
            'patient',
            'sampleRequest',
            'sampleRequest.bloodSample.assignedLabStaff',
            'sampleRequest.bloodSample.transportations.laboratory',
            'sampleRequest.bloodSample.transportations.transporter',
        ])
            ->where(
                'assigned_collector_id',
                Auth::id()
            )
            ->orderBy('preferred_date')
            ->orderByRaw(
                'CASE WHEN route_order IS NULL THEN 1 ELSE 0 END'
            )
            ->orderBy('route_order')
            ->orderBy('preferred_time')
            ->get();

        return view(
            'home-collections.collector_index',
            compact('homeCollections')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | COLLECTOR - START TRIP TO PATIENT
    |--------------------------------------------------------------------------
    */

    public function startTrip(
        HomeCollectionRequest $homeCollection
    ): RedirectResponse {

        $this->authorizeCollector(
            $homeCollection
        );

        if ($homeCollection->status !== 'assigned') {
            return back()->with(
                'error',
                'This trip cannot be started from its current status.'
            );
        }

        $homeCollection->update([
            'status' => 'on_the_way',
            'on_the_way_at' => now(),
        ]);

        $homeCollection->loadMissing([
            'patient',
            'assignedCollector',
            'sampleRequest.bloodSample',
        ]);

        $homeCollection->patient?->notify(
            new PatientSampleUpdateNotification(
                kind: 'home_collector_on_the_way',

                title: 'Collector is on the way',

                message:
                    (
                        $homeCollection
                            ->assignedCollector
                            ?->name
                        ?? 'Your assigned collector'
                    )
                    .' is travelling to your home for the scheduled sample collection.',

                sampleRequestId:
                    $homeCollection->sample_request_id,

                sampleCode:
                    $homeCollection
                        ->sampleRequest
                        ?->bloodSample
                        ?->sample_code,

                actionLabel:
                    'Track Collection',
            )
        );

        return back()->with(
            'success',
            'Trip started successfully. The patient can now see that you are on the way.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | COLLECTOR - ARRIVED AT PATIENT
    |--------------------------------------------------------------------------
    */

    public function markArrived(
        HomeCollectionRequest $homeCollection
    ): RedirectResponse {

        $this->authorizeCollector(
            $homeCollection
        );

        if ($homeCollection->status !== 'on_the_way') {
            return back()->with(
                'error',
                'You must start the trip before marking arrival.'
            );
        }

        $homeCollection->update([
            'status' => 'arrived',
            'arrived_at' => now(),
        ]);

        $homeCollection->loadMissing([
            'patient',
            'assignedCollector',
            'sampleRequest.bloodSample',
        ]);

        $homeCollection->patient?->notify(
            new PatientSampleUpdateNotification(
                kind: 'home_collector_arrived',

                title: 'Collector has arrived',

                message:
                    (
                        $homeCollection
                            ->assignedCollector
                            ?->name
                        ?? 'Your assigned collector'
                    )
                    .' has arrived for your home sample collection.',

                sampleRequestId:
                    $homeCollection->sample_request_id,

                sampleCode:
                    $homeCollection
                        ->sampleRequest
                        ?->bloodSample
                        ?->sample_code,

                actionLabel:
                    'Track Collection',
            )
        );

        return back()->with(
            'success',
            'Arrival confirmed successfully.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | COLLECTOR - CONFIRM SAMPLE COLLECTION
    |--------------------------------------------------------------------------
    */

    public function markCollected(
        HomeCollectionRequest $homeCollection
    ): RedirectResponse {

        $this->authorizeCollector(
            $homeCollection
        );

        if ($homeCollection->status !== 'arrived') {
            return back()->with(
                'error',
                'You must confirm arrival before collecting the sample.'
            );
        }

        $homeCollection->load(
            'sampleRequest.bloodSample'
        );

        $bloodSample =
            $homeCollection
                ->sampleRequest
                ?->bloodSample;

        if (!$bloodSample) {
            return back()->with(
                'error',
                'No Blood Sample is connected to this home collection request.'
            );
        }

        $homeCollection->update([
            'status' => 'collected',
            'collected_at' => now(),
        ]);

        $bloodSample->update([
            'collected_by' => Auth::id(),
            'collected_at' => now(),
        ]);

        return back()->with(
            'success',
            'Blood Sample collected successfully. Admin can now assign Lab Staff.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | PRIVATE - AUTHORIZE SAMPLE COLLECTOR
    |--------------------------------------------------------------------------
    */

    private function authorizeCollector(
        HomeCollectionRequest $homeCollection
    ): void {

        if (
            !Auth::check()
            || Auth::user()->role !== 'sample_collector'
        ) {
            abort(403);
        }

        if (
            (int) $homeCollection->assigned_collector_id
            !==
            (int) Auth::id()
        ) {
            abort(403);
        }
    }
}
