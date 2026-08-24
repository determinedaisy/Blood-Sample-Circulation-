<?php

namespace App\Http\Controllers;

use App\Models\BloodSample;
use App\Models\SampleHistory;
use Illuminate\Http\Request;

class SampleHistoryController extends Controller
{
    /**
     * Generate and display the history for a specific blood sample.
     */
    public function show($sample_code)
    {
        // Find the sample by its unique SMP code (e.g., SMP-001)
        $sample = BloodSample::where('sample_code', $sample_code)->firstOrFail();

        // Fetch the compiled history from the database, ordered from oldest to newest
        $histories = SampleHistory::with('user')
                        ->where('blood_sample_id', $sample->id)
                        ->orderBy('created_at', 'asc')
                        ->get();

        // Pass the data to the frontend view
        return view('history.show', compact('sample', 'histories'));
    }
}