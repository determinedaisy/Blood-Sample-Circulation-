<?php

namespace App\Http\Controllers;

use App\Models\HomeCollectionRequest;
use App\Models\Laboratory;
use App\Models\SampleTransportation;
use App\Models\User;
use App\Services\LaboratoryCapacityService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'sampleRequest.bloodSample.transportations.transporter',
            'sampleRequest.bloodSample.transportations.collectionCenter',
            'sampleRequest.bloodSample.transportations.laboratory',
        ])
            ->orderBy('preferred_date')
            ->orderBy('preferred_time')
            ->get();

        $collectors = User::where(
            'role',
            'sample_collector'
        )
            ->orderBy('name')
            ->get();

        /*
         * Laboratories are needed after a home
         * sample has been collected.
         */
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
                'laboratories'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ADMIN - ASSIGN HOME COLLECTOR
    |--------------------------------------------------------------------------
    */

    public function assignCollector(
        Request $request,
        HomeCollectionRequest $homeCollection
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
        'This sample request must be approved before a home collector can be assigned.'
    );
}
        if ($homeCollection->status !== 'pending') {
            return back()->with(
                'error',
                'Only pending home collection requests can be assigned.'
            );
        }

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
            return back()->with(
                'error',
                'The selected user is not a sample collector.'
            );
        }

        $homeCollection->update([
            'assigned_collector_id' => $collector->id,
            'status' => 'assigned',
            'assigned_at' => now(),
        ]);

        return back()->with(
            'success',
            'Home collection collector assigned successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ADMIN - SEND HOME SAMPLE TO LABORATORY
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

        /*
         * The linked sample request must still be approved.
         */
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

        /*
         * Blood must actually have been collected.
         */
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

        /*
         * Only active laboratories can be selected.
         */
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

        /*
         * Load the SAME BloodSample that was
         * collected from the patient's home.
         */
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

        /*
         * Prevent duplicate transportation records.
         */
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

        /*
         * Create transportation using the existing
         * transportation system.
         *
         * collection_center_id = NULL means:
         * origin = patient's home.
         */
        $capacityService->schedule([
            'blood_sample_id' => $bloodSample->id,

            'collection_center_id' => null,

            'laboratory_id' => $laboratory->id,

            /*
             * For now the home collector is also
             * responsible for carrying the sample
             * to the laboratory.
             */
            'transported_by' =>
                $homeCollection->assigned_collector_id,

            'status' => 'pending',

            'departure_time' => null,
            'arrival_time' => null,

            'notes' =>
                'Home collection sample - transport from patient home to laboratory.',
        ], $validated['scheduled_test_date']);

        return back()->with(
            'success',
            'Sample sent to transportation queue and a testing slot was reserved at '.$laboratory->name.'.'
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
            'sampleRequest.bloodSample.transportations.laboratory',
            'sampleRequest.bloodSample.transportations.transporter',
        ])
            ->where(
                'assigned_collector_id',
                Auth::id()
            )
            ->orderBy('preferred_date')
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

        $this->authorizeCollector($homeCollection);

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

        $this->authorizeCollector($homeCollection);

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

        $this->authorizeCollector($homeCollection);

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
                'No blood sample is connected to this home collection request.'
            );
        }

        /*
         * Complete the home collection.
         */
        $homeCollection->update([
            'status' => 'collected',
            'collected_at' => now(),
        ]);

        /*
         * Update the SAME BloodSample.
         */
        $bloodSample->update([
            'collected_by' => Auth::id(),
            'collected_at' => now(),
        ]);

        return back()->with(
            'success',
            'Blood sample collected successfully. It is now waiting for laboratory transportation.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PRIVATE AUTHORIZATION HELPER
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

        /*
         * A collector may modify ONLY their
         * own assigned home collection.
         */
        if (
            (int) $homeCollection->assigned_collector_id
            !==
            (int) Auth::id()
        ) {
            abort(403);
        }
    }
}
