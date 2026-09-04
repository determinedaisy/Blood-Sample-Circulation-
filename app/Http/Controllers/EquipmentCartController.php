<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use Illuminate\Http\Request;

class EquipmentCartController extends Controller
{
    public function index(Request $request)
    {
        $cart = $request->session()->get('equipment_cart', []);

        $equipmentIds = array_keys($cart);

        $equipment = Equipment::whereIn('id', $equipmentIds)
            ->get()
            ->keyBy('id');

        $cartItems = [];
        $total = 0;

        foreach ($cart as $equipmentId => $quantity) {
            if (!isset($equipment[$equipmentId])) {
                continue;
            }

            $item = $equipment[$equipmentId];

            $subtotal = (float) $item->price * $quantity;

            $cartItems[] = [
                'equipment' => $item,
                'quantity' => $quantity,
                'subtotal' => $subtotal,
            ];

            $total += $subtotal;
        }

        return view(
            'equipment.cart',
            compact('cartItems', 'total')
        );
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'equipment_id' => [
                'required',
                'integer',
                'exists:equipment,id',
            ],
            'quantity' => [
                'required',
                'integer',
                'min:1',
            ],
        ]);

        $equipment = Equipment::findOrFail(
            $validated['equipment_id']
        );

        $cart = $request->session()->get(
            'equipment_cart',
            []
        );

        $currentQuantity = $cart[$equipment->id] ?? 0;

        $newQuantity =
            $currentQuantity +
            $validated['quantity'];

        if ($newQuantity > $equipment->stock) {
            return back()->with(
                'error',
                "Only {$equipment->stock} units of {$equipment->name} are currently available."
            );
        }

        $cart[$equipment->id] = $newQuantity;

        $request->session()->put(
            'equipment_cart',
            $cart
        );

        return back()->with(
            'success',
            "{$equipment->name} has been added to your cart."
        );
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'quantities' => [
                'required',
                'array',
            ],
            'quantities.*' => [
                'required',
                'integer',
                'min:1',
            ],
        ]);

        $cart = $request->session()->get(
            'equipment_cart',
            []
        );

        foreach (
            $validated['quantities']
            as $equipmentId => $quantity
        ) {
            if (!isset($cart[$equipmentId])) {
                continue;
            }

            $equipment = Equipment::find($equipmentId);

            if (!$equipment) {
                unset($cart[$equipmentId]);
                continue;
            }

            if ($quantity > $equipment->stock) {
                return back()->with(
                    'error',
                    "Only {$equipment->stock} units of {$equipment->name} are available."
                );
            }

            $cart[$equipmentId] = $quantity;
        }

        $request->session()->put(
            'equipment_cart',
            $cart
        );

        return back()->with(
            'success',
            'Your cart has been updated.'
        );
    }

    public function remove(
        Request $request,
        Equipment $equipment
    ) {
        $cart = $request->session()->get(
            'equipment_cart',
            []
        );

        unset($cart[$equipment->id]);

        $request->session()->put(
            'equipment_cart',
            $cart
        );

        return back()->with(
            'success',
            "{$equipment->name} has been removed from your cart."
        );
    }
}