<?php

namespace App\Http\Controllers;

use App\Models\BloodSample;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    /**
     * Display blood samples in the inventory.
     */
    public function index(Request $request)
    {
        if (
            !auth()->check()
            || !in_array(
                auth()->user()->role,
                ['sample_collector', 'lab_staff', 'admin'],
                true
            )
        ) {
            abort(403);
        }

        $filter = $request->query('filter', 'available');

        if (!in_array($filter, ['available', 'collected', 'all'], true)) {
            $filter = 'available';
        }

        $query = BloodSample::with([
            'patient',
            'reviewer',
            'collector',
        ])
            ->where('status', 'accepted');

        if ($filter === 'available') {
            $query->whereNull('collected_by');
        }

        if ($filter === 'collected') {
            $query->whereNotNull('collected_by');
        }

        $bloodSamples = $query
            ->latest('reviewed_at')
            ->get();

        $totalSamples = BloodSample::where('status', 'accepted')->count();

        $availableSamples = BloodSample::where('status', 'accepted')
            ->whereNull('collected_by')
            ->count();

        $collectedSamples = BloodSample::where('status', 'accepted')
            ->whereNotNull('collected_by')
            ->count();

        return view(
            'inventory.index',
            compact(
                'bloodSamples',
                'filter',
                'totalSamples',
                'availableSamples',
                'collectedSamples'
            )
        );
    }

    /**
     * Mark an accepted blood sample as collected.
     */
    public function collect(
        BloodSample $bloodSample
    ): RedirectResponse {

        if (
            !auth()->check()
            || auth()->user()->role !== 'sample_collector'
        ) {
            abort(403);
        }

        if ($bloodSample->status !== 'accepted') {
            return back()->with(
                'error',
                'Only accepted blood samples can be collected.'
            );
        }

        if ($bloodSample->collected_by !== null) {
            return back()->with(
                'error',
                'This blood sample has already been collected.'
            );
        }

        DB::transaction(function () use ($bloodSample) {

            $bloodSample->update([
                'collected_by' => auth()->id(),
            ]);
        });

        return back()->with(
            'success',
            'Blood sample collected successfully.'
        );
    }
}