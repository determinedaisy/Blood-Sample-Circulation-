<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    // Displays the list of all inventory records
    public function index()
    {
        $inventories = Inventory::all();
        return view('inventory.index', compact('inventories'));
    }

    // Shows the form to add a new inventory record
    public function create()
    {
        return view('inventory.create');
    }

    // Saves the submitted form data securely into the database
    public function store(Request $request)
    {
        $request->validate([
            'blood_sample_id' => 'required|integer',
            'refrigerator' => 'required|string|max:255',
            'shelf' => 'required|string|max:255',
            'rack' => 'required|string|max:255',
            'storage_location' => 'required|string|max:255',
        ]);

        Inventory::create($request->all());

        return redirect()->route('inventory.index')
                         ->with('success', 'Blood sample successfully added to laboratory inventory.');
    }
}