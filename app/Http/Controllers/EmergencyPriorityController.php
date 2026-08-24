<?php

namespace App\Http\Controllers;

use App\Models\EmergencyRequest;
use Illuminate\Http\Request;

class EmergencyPriorityController extends Controller
{
    public function index()
    {
        abort_unless(
            auth()->check() &&
            in_array(auth()->user()->role, ['admin', 'doctor']),
            403
        );

        $requests = EmergencyRequest::with('patient')
            ->orderByRaw("
                CASE priority
                    WHEN 'critical' THEN 1
                    WHEN 'urgent' THEN 2
                    WHEN 'normal' THEN 3
                    ELSE 4
                END
            ")
            ->latest()
            ->get();

        $criticalCount = EmergencyRequest::where(
            'priority',
            'critical'
        )->count();

        $urgentCount = EmergencyRequest::where(
            'priority',
            'urgent'
        )->count();

        $normalCount = EmergencyRequest::where(
            'priority',
            'normal'
        )->count();

        return view('admin.emergency-priority', compact(
            'requests',
            'criticalCount',
            'urgentCount',
            'normalCount'
        ));
    }

    public function update(
        Request $request,
        EmergencyRequest $emergencyRequest
    ) {
        abort_unless(
            auth()->check() &&
            in_array(auth()->user()->role, ['admin', 'doctor']),
            403
        );

        $validated = $request->validate([
            'priority' => 'required|in:normal,urgent,critical',
            'priority_reason' => 'nullable|string|max:500',
        ]);

        $emergencyRequest->update($validated);

        return back()->with(
            'success',
            'Emergency request priority updated successfully.'
        );
    }
}