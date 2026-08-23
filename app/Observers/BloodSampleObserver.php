<?php

namespace App\Observers;

use App\Models\BloodSample;
use App\Models\SampleHistory;
use Illuminate\Support\Facades\Auth;

class BloodSampleObserver
{
    /**
     * Handle the BloodSample "created" event.
     * This runs automatically the exact moment a new sample is collected.
     */
    public function created(BloodSample $bloodSample): void
    {
        SampleHistory::create([
            'blood_sample_id' => $bloodSample->id,
            // Uses the collector's ID if available, otherwise the logged-in user
            'user_id' => $bloodSample->collected_by ?? Auth::id(), 
            'action' => 'Collection',
            'description' => 'Blood sample was officially collected and logged into the system.',
            'location' => 'Collection Center', 
        ]);
    }

    /**
     * Handle the BloodSample "updated" event.
     * This runs automatically anytime a sample's data is modified.
     */
    public function updated(BloodSample $bloodSample): void
    {
        // Only log history if the 'status' column specifically was changed
        if ($bloodSample->isDirty('status')) {
            SampleHistory::create([
                'blood_sample_id' => $bloodSample->id,
                'user_id' => Auth::id(),
                'action' => 'Status Update',
                'description' => 'Sample status was updated to: ' . $bloodSample->status,
                'location' => 'System Update',
            ]);
        }
    }
}