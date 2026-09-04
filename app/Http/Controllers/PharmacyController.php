<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PharmacyController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(
            Auth::check() && Auth::user()->role === 'patient',
            403
        );

        $query = Medicine::query()
            ->where('stock', '>', 0);

        if ($request->filled('search')) {
            $search = $request->input('search');

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            });
        }

        $medicines = $query
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('pharmacy.index', compact('medicines'));
    }
}