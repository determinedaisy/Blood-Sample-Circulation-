<?php

namespace App\Http\Controllers;

use App\Models\Laboratory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaboratoryCapacityController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::check() || !in_array(Auth::user()->role, ['admin', 'lab_staff'], true)) {
            abort(403);
        }

        $validated = $request->validate([
            'date' => ['nullable', 'date'],
        ]);

        $selectedDate = $validated['date'] ?? now()->toDateString();

        $laboratories = Laboratory::query()
            ->withCount([
                'transportations as scheduled_count' => fn ($query) =>
                    $query->whereDate('scheduled_test_date', $selectedDate),
            ])
            ->orderBy('name')
            ->get();

        return view('laboratory-capacity.index', compact('laboratories', 'selectedDate'));
    }

    public function update(Request $request, Laboratory $laboratory): RedirectResponse
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'daily_capacity' => ['required', 'integer', 'min:1', 'max:10000'],
        ]);

        $laboratory->update([
            'daily_capacity' => $validated['daily_capacity'],
        ]);

        return back()->with('success', "Daily capacity updated for {$laboratory->name}.");
    }
}
