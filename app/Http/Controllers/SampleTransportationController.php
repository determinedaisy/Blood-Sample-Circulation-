<?php

namespace App\Http\Controllers;

use App\Models\BloodSample;
use App\Models\CollectionCenter;
use App\Models\Laboratory;
use App\Models\SampleTransportation;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SampleTransportationController extends Controller
{
    /**
     * Show transportation records.
     */
    public function index(Request $request)
    {
        if (
            !auth()->check()
            || !in_array(
                auth()->user()->role,
                ['admin', 'lab_staff','sample_collector'],
                true
            )
        ) {
            abort(403);
        }

        $filter = $request->query('filter', 'all');

        if (!in_array($filter, ['all', 'pending', 'in_transit', 'delivered'], true)) {
            $filter = 'all';
        }

     $query = SampleTransportation::with([
    'bloodSample',
    'collectionCenter',
    'laboratory',
    'transporter',
]);

// Sample collectors should only see their own assigned transportations.
if (auth()->user()->role === 'sample_collector') {
    $query->where('transported_by', auth()->id());
}
        if ($filter !== 'all') {
            $query->where('status', $filter);
        }

        $transportations = $query
            ->latest()
            ->get();

     $countQuery = SampleTransportation::query();

if (auth()->user()->role === 'sample_collector') {
    $countQuery->where('transported_by', auth()->id());
}

$pendingCount = (clone $countQuery)
    ->where('status', 'pending')
    ->count();

$inTransitCount = (clone $countQuery)
    ->where('status', 'in_transit')
    ->count();

$deliveredCount = (clone $countQuery)
    ->where('status', 'delivered')
    ->count();

        $bloodSamples = BloodSample::where('status', 'received')
            ->get();

        $collectionCenters = CollectionCenter::where('is_active', true)
            ->get();

        $laboratories = Laboratory::where('is_active', true)
            ->get();

        $collectors = User::where('role', 'sample_collector')
            ->get();

        return view(
            'transportation.index',
            compact(
                'transportations',
                'filter',
                'pendingCount',
                'inTransitCount',
                'deliveredCount',
                'bloodSamples',
                'collectionCenters',
                'laboratories',
                'collectors'
            )
        );
    }

    /**
     * Create a new transportation record.
     */
    public function store(Request $request): RedirectResponse
    {
        if (
            !auth()->check()
            || !in_array(
                auth()->user()->role,
                ['admin', 'lab_staff'],
                true
            )
        ) {
            abort(403);
        }

        $validated = $request->validate([
            'blood_sample_id' => ['required', 'exists:blood_samples,id'],
            'collection_center_id' => ['required', 'exists:collection_centers,id'],
            'laboratory_id' => ['required', 'exists:laboratories,id'],
            'transported_by' => ['required', 'exists:users,id'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $alreadyExists = SampleTransportation::where(
            'blood_sample_id',
            $validated['blood_sample_id']
        )
            ->whereIn('status', ['pending', 'in_transit'])
            ->exists();

        if ($alreadyExists) {
            return back()->with(
                'error',
                'This blood sample already has an active transportation record.'
            );
        }

        SampleTransportation::create([
            ...$validated,
            'status' => 'pending',
        ]);

        return back()->with(
            'success',
            'Transportation record created successfully.'
        );
    }

    /**
     * Start transportation.
     */
    public function start(
        SampleTransportation $transportation
    ): RedirectResponse {

        if (
            !auth()->check()
            || auth()->user()->role !== 'sample_collector'
        ) {
            abort(403);
        }

        if ($transportation->status !== 'pending') {
            return back()->with(
                'error',
                'Only pending transportation can be started.'
            );
        }

        if ((int) $transportation->transported_by !== (int) auth()->id()) {
            return back()->with(
                'error',
                'You are not assigned to this transportation.'
            );
        }

        $transportation->update([
            'status' => 'in_transit',
            'departure_time' => now(),
        ]);

        return back()->with(
            'success',
            'Transportation started successfully.'
        );
    }

    /**
     * Mark transportation as delivered.
     */
    public function deliver(
        SampleTransportation $transportation
    ): RedirectResponse {

       if (
    !auth()->check()
    || auth()->user()->role !== 'sample_collector'
    || (int) $transportation->transported_by !== (int) auth()->id()
) {
    abort(403);

        }

        if ($transportation->status !== 'in_transit') {
            return back()->with(
                'error',
                'Only samples currently in transit can be marked as delivered.'
            );
        }

        $transportation->update([
            'status' => 'delivered',
            'arrival_time' => now(),
        ]);

        return back()->with(
            'success',
            'Sample delivered successfully.'
        );
    }
}