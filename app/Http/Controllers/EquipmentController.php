<?php

namespace App\Http\Controllers;

use App\Models\Equipment;

class EquipmentController extends Controller
{
    /**
     * Display all equipment products.
     */
    public function index()
    {
        $equipment = Equipment::orderBy('name')->get();

        return view('equipment.index', compact('equipment'));
    }
}
