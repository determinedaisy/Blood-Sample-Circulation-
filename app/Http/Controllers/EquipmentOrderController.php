<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\EquipmentOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EquipmentOrderController extends Controller
{
    public function checkout(Request $request)
    {
        $cart = $request->session()->get(
            'equipment_cart',
            []
        );

        if (empty($cart)) {
            return redirect()
                ->route('equipment.cart')
                ->with(
                    'error',
                    'Your cart is empty.'
                );
        }

        $equipmentIds = array_keys($cart);

        $equipment = Equipment::whereIn(
            'id',
            $equipmentIds
        )
            ->get()
            ->keyBy('id');

        $cartItems = [];
        $total = 0;

        foreach ($cart as $equipmentId => $quantity) {
            if (!isset($equipment[$equipmentId])) {
                continue;
            }

            $item = $equipment[$equipmentId];

            $subtotal =
                (float) $item->price *
                $quantity;

            $cartItems[] = [
                'equipment' => $item,
                'quantity' => $quantity,
                'subtotal' => $subtotal,
            ];

            $total += $subtotal;
        }

        if (empty($cartItems)) {
            return redirect()
                ->route('equipment.cart')
                ->with(
                    'error',
                    'Your cart is empty.'
                );
        }

        return view(
            'equipment.checkout',
            compact(
                'cartItems',
                'total'
            )
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'delivery_address' => [
                'required',
                'string',
                'min:10',
                'max:1000',
            ],
        ]);

        $cart = $request->session()->get(
            'equipment_cart',
            []
        );

        if (empty($cart)) {
            return redirect()
                ->route('equipment.cart')
                ->with(
                    'error',
                    'Your cart is empty.'
                );
        }

        try {
            $order = DB::transaction(function () use (
                $cart,
                $validated,
                $request
            ) {
                $total = 0;
                $orderItems = [];

                foreach ($cart as $equipmentId => $quantity) {
                    $equipment = Equipment::where(
                        'id',
                        $equipmentId
                    )
                        ->lockForUpdate()
                        ->first();

                    if (!$equipment) {
                        throw new \RuntimeException(
                            'One of the products in your cart no longer exists.'
                        );
                    }

                    if ($quantity > $equipment->stock) {
                        throw new \RuntimeException(
                            "Not enough stock for {$equipment->name}. "
                            . "Only {$equipment->stock} unit(s) are available."
                        );
                    }

                    $price = (float) $equipment->price;

                    $subtotal =
                        $price *
                        $quantity;

                    $total += $subtotal;

                    $orderItems[] = [
                        'equipment' => $equipment,
                        'quantity' => $quantity,
                        'price' => $price,
                        'subtotal' => $subtotal,
                    ];
                }

                $order = EquipmentOrder::create([
                    'user_id' => $request->user()->id,
                    'total_amount' => $total,
                    'delivery_address' =>
                        $validated['delivery_address'],
                    'status' => 'pending',
                ]);

                foreach ($orderItems as $item) {
                    $order->items()->create([
                        'equipment_id' =>
                            $item['equipment']->id,
                        'quantity' =>
                            $item['quantity'],
                        'price' =>
                            $item['price'],
                        'subtotal' =>
                            $item['subtotal'],
                    ]);

                    $item['equipment']->decrement(
                        'stock',
                        $item['quantity']
                    );
                }

                return $order;
            });

            $request->session()->forget(
                'equipment_cart'
            );

            return redirect()
                ->route(
                    'equipment.order.success',
                    $order
                )
                ->with(
                    'success',
                    'Your equipment order has been placed successfully.'
                );
        } catch (\RuntimeException $exception) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    $exception->getMessage()
                );
        }
    }

    public function success(
        EquipmentOrder $order
    ) {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load(
            'items.equipment'
        );

        return view(
            'equipment.success',
            compact('order')
        );
    }

    public function orders(Request $request)
    {
        $orders = EquipmentOrder::where(
            'user_id',
            $request->user()->id
        )
            ->with(
                'items.equipment'
            )
            ->latest()
            ->get();

        return view(
            'equipment.orders',
            compact('orders')
        );
    }

    public function show(
        Request $request,
        EquipmentOrder $order
    ) {
        if (
            $order->user_id !==
            $request->user()->id
        ) {
            abort(403);
        }

        $order->load(
            'items.equipment'
        );

        return view(
            'equipment.order-details',
            compact('order')
        );
    }
}