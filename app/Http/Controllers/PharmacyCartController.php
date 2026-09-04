<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PharmacyCartController extends Controller
{
    private function ensurePatient(): void
    {
        abort_unless(
            Auth::check() && Auth::user()->role === 'patient',
            403
        );
    }

    public function index()
    {
        $this->ensurePatient();

        $cart = session()->get('pharmacy_cart', []);

        $total = collect($cart)->sum(
            fn ($item) => $item['price'] * $item['quantity']
        );

        return view('pharmacy.cart', compact('cart', 'total'));
    }

    public function add(Request $request, Medicine $medicine)
    {
        $this->ensurePatient();

        $request->validate([
            'quantity' => [
                'required',
                'integer',
                'min:1',
            ],
        ]);

        if ($medicine->stock < 1) {
            return back()->with(
                'error',
                'This medicine is out of stock.'
            );
        }

        $quantity = (int) $request->quantity;

        $cart = session()->get('pharmacy_cart', []);

        if (isset($cart[$medicine->id])) {
            $newQuantity =
                $cart[$medicine->id]['quantity'] + $quantity;

            if ($newQuantity > $medicine->stock) {
                return back()->with(
                    'error',
                    'You cannot add more than the available stock.'
                );
            }

            $cart[$medicine->id]['quantity'] = $newQuantity;
        } else {
            if ($quantity > $medicine->stock) {
                return back()->with(
                    'error',
                    'Requested quantity exceeds available stock.'
                );
            }

            $cart[$medicine->id] = [
                'medicine_id' => $medicine->id,
                'name' => $medicine->name,
                'price' => (float) $medicine->price,
                'quantity' => $quantity,
            ];
        }

        session()->put('pharmacy_cart', $cart);

        return redirect()
            ->route('pharmacy.cart')
            ->with('success', 'Medicine added to cart.');
    }

    public function update(Request $request, $medicineId)
    {
        $this->ensurePatient();

        $request->validate([
            'quantity' => [
                'required',
                'integer',
                'min:1',
            ],
        ]);

        $cart = session()->get('pharmacy_cart', []);

        if (!isset($cart[$medicineId])) {
            return back()->with(
                'error',
                'Medicine is not in your cart.'
            );
        }

        $medicine = Medicine::findOrFail($medicineId);

        $quantity = (int) $request->quantity;

        if ($quantity > $medicine->stock) {
            return back()->with(
                'error',
                'Requested quantity exceeds available stock.'
            );
        }

        $cart[$medicineId]['quantity'] = $quantity;
        $cart[$medicineId]['price'] = (float) $medicine->price;

        session()->put('pharmacy_cart', $cart);

        return back()->with(
            'success',
            'Cart updated.'
        );
    }

    public function remove($medicineId)
    {
        $this->ensurePatient();

        $cart = session()->get('pharmacy_cart', []);

        unset($cart[$medicineId]);

        session()->put('pharmacy_cart', $cart);

        return back()->with(
            'success',
            'Medicine removed from cart.'
        );
    }
}
