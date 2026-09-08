<?php

namespace App\Http\Controllers;

use App\Models\BloodSample;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class InventoryController extends Controller
{
    // Displays the list of all inventory records
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'all');

        if (!in_array($filter, ['available', 'collected', 'all'], true)) {
            $filter = 'all';
        }

        $query = BloodSample::with([
            'patient',
            'reviewer',
            'collector',
        ])
            ->where('status', 'accepted');

        $bloodSamples = $query->get();

        $totalSamples = BloodSample::where('status', 'accepted')->count();

        $availableSamples = BloodSample::where('status', 'accepted')
            ->whereNull('collected_by')
            ->count();

        $collectedSamples = BloodSample::where('status', 'accepted')
            ->whereNotNull('collected_by')
            ->count();

        $inventories = Inventory::all();

        return view('inventory.index', compact(
            'inventories',
            'bloodSamples',
            'filter',
            'totalSamples',
            'availableSamples',
            'collectedSamples'
        ));
    }

    // Shows the form to add a new inventory record
    public function create()
    {
        $bloodSamples = BloodSample::where('status', 'accepted')
            ->orderBy('sample_code')
            ->get();

        return view('inventory.create', compact('bloodSamples'));
    }

    // Stores inventory data
    public function store(Request $request)
    {
        $request->validate([
            'blood_sample_id' => [
                'required',
                Rule::exists('blood_samples', 'id')
                    ->where(fn ($query) => $query->where('status', 'accepted')),
            ],
            'refrigerator' => 'required|string|max:255',
            'shelf' => 'required|string|max:255',
            'rack' => 'required|string|max:255',
            'storage_location' => 'required|string|max:255',
        ]);

        Inventory::create([
            'blood_sample_id' => $request->blood_sample_id,
            'refrigerator' => $request->refrigerator,
            'shelf' => $request->shelf,
            'rack' => $request->rack,
            'storage_location' => $request->storage_location,
        ]);

        return redirect()
            ->route('inventory.index')
            ->with('success', 'Blood sample successfully added to laboratory inventory.');
    }

    // Collect sample
    public function collect(Inventory $inventory)
    {
        $inventory->update([
            'status' => 'collected',
        ]);

        return redirect()
            ->route('inventory.index')
            ->with('success', 'Blood sample collected successfully.');
    }
}
