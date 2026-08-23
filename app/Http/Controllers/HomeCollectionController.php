<?php

namespace App\Http\Controllers;

use App\Models\HomeCollectionRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeCollectionController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | ADMIN
    |--------------------------------------------------------------------------
    */

    /**
     * Admin: view all home collection requests.
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
            'sampleRequest.bloodSample',
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

        return view(
            'home-collections.admin_index',
            compact(
                'homeCollections',
                'collectors'
            )
        );
    }


    /**
     * Admin: assign collector to home collection request.
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
            'assigned_collector_id' =>
                $collector->id,

            'status' =>
                'assigned',

            'assigned_at' =>
                now(),
        ]);

        return back()->with(
            'success',
            'Home collection collector assigned successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SAMPLE COLLECTOR
    |--------------------------------------------------------------------------
    */

    /**
     * Collector: view only home collections assigned to them.
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
            'sampleRequest.bloodSample',
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


    /**
     * Collector: start travelling to patient.
     */
    public function startTrip(
        HomeCollectionRequest $homeCollection
    ): RedirectResponse {

        if (
            !Auth::check()
            || Auth::user()->role !== 'sample_collector'
        ) {
            abort(403);
        }

        /*
         * Collector can only update their own assignment.
         */
        if (
            (int) $homeCollection->assigned_collector_id
            !==
            (int) Auth::id()
        ) {
            abort(403);
        }

        /*
         * Correct sequence:
         * assigned -> on_the_way
         */
        if ($homeCollection->status !== 'assigned') {

            return back()->with(
                'error',
                'This trip cannot be started from its current status.'
            );
        }

        $homeCollection->update([
            'status' =>
                'on_the_way',

            'on_the_way_at' =>
                now(),
        ]);

        return back()->with(
            'success',
            'Trip started successfully. The patient can now see that you are on the way.'
        );
    }


    /**
     * Collector: confirm arrival at patient's location.
     */
    public function markArrived(
        HomeCollectionRequest $homeCollection
    ): RedirectResponse {

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

        /*
         * Correct sequence:
         * on_the_way -> arrived
         */
        if ($homeCollection->status !== 'on_the_way') {

            return back()->with(
                'error',
                'You must start the trip before marking arrival.'
            );
        }

        $homeCollection->update([
            'status' =>
                'arrived',

            'arrived_at' =>
                now(),
        ]);

        return back()->with(
            'success',
            'Arrival confirmed successfully.'
        );
    }


    /**
     * Collector: confirm sample collection.
     */
    public function markCollected(
        HomeCollectionRequest $homeCollection
    ): RedirectResponse {

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

        /*
         * Correct sequence:
         * arrived -> collected
         */
        if ($homeCollection->status !== 'arrived') {

            return back()->with(
                'error',
                'You must confirm arrival before collecting the sample.'
            );
        }

        /*
         * Load the connected blood sample.
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
                'No blood sample is connected to this home collection request.'
            );
        }


        /*
         * Mark home collection completed.
         */
        $homeCollection->update([
            'status' =>
                'collected',

            'collected_at' =>
                now(),
        ]);


        /*
         * Also update the SAME BloodSample.
         *
         * This connects Feature 14 to your
         * existing sample lifecycle/tracking.
         */
        $bloodSample->update([
            'collected_by' =>
                Auth::id(),

            'collected_at' =>
                now(),
        ]);


        return back()->with(
            'success',
            'Blood sample collection confirmed successfully.'
        );
    }
}