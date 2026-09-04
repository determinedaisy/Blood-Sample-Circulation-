<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\PharmacyOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PharmacyOrderController extends Controller
{
    private function ensurePatient(): void
    {
        abort_unless(
            Auth::check() &&
            Auth::user()->role === 'patient',
            403
        );
    }

    private function ensureDoctor(): void
    {
        abort_unless(
            Auth::check() &&
            Auth::user()->role === 'doctor',
            403
        );
    }

    public function checkout()
    {
        $this->ensurePatient();

        $cart = session()->get('pharmacy_cart', []);

        if (empty($cart)) {
            return redirect()
                ->route('pharmacy.index')
                ->with(
                    'error',
                    'Your pharmacy cart is empty.'
                );
        }

        $total = collect($cart)->sum(
            fn ($item) =>
                $item['price'] * $item['quantity']
        );

        return view(
            'pharmacy.checkout',
            compact('cart', 'total')
        );
    }

    public function store(Request $request)
    {
        $this->ensurePatient();

        $validated = $request->validate([
            'delivery_address' => [
                'required',
                'string',
                'max:1000',
            ],
        ]);

        $cart = session()->get('pharmacy_cart', []);

        if (empty($cart)) {
            return redirect()
                ->route('pharmacy.index')
                ->with(
                    'error',
                    'Your pharmacy cart is empty.'
                );
        }

        $order = DB::transaction(function () use (
            $cart,
            $validated
        ) {
            $total = 0;
            $medicines = [];

            /*
             * Lock medicines while checking their current stock.
             */
            foreach ($cart as $item) {

                $medicine = Medicine::lockForUpdate()
                    ->findOrFail($item['medicine_id']);

                if ($medicine->stock < $item['quantity']) {
                    abort(
                        422,
                        "Not enough stock for {$medicine->name}."
                    );
                }

                $medicines[$medicine->id] = $medicine;

                $total +=
                    $medicine->price * $item['quantity'];
            }

            /*
             * Create the order as pending.
             *
             * Stock is intentionally NOT reduced here.
             * Stock will be reduced when a doctor accepts
             * the order.
             */
            $order = PharmacyOrder::create([
                'user_id' => Auth::id(),
                'delivery_address' =>
                    $validated['delivery_address'],
                'status' => 'pending',
                'total_amount' => $total,
            ]);

            /*
             * Create each order item using the current
             * database price.
             */
            foreach ($cart as $item) {

                $medicine = $medicines[$item['medicine_id']];

                $order->items()->create([
                    'medicine_id' => $medicine->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $medicine->price,
                    'subtotal' =>
                        $medicine->price *
                        $item['quantity'],
                ]);
            }

            return $order;
        });

        session()->forget('pharmacy_cart');

        return redirect()
            ->route(
                'pharmacy.orders.show',
                $order
            )
            ->with(
                'success',
                'Your pharmacy order has been submitted and is waiting for doctor confirmation.'
            );
    }

    public function orders()
    {
        $this->ensurePatient();

        $orders = PharmacyOrder::where(
            'user_id',
            Auth::id()
        )
            ->with('items.medicine')
            ->latest()
            ->paginate(10);

        return view(
            'pharmacy.orders',
            compact('orders')
        );
    }

    public function show(PharmacyOrder $order)
    {
        $this->ensurePatient();

        abort_unless(
            $order->user_id === Auth::id(),
            403
        );

        $order->load([
            'items.medicine',
            'doctor',
        ]);

        return view(
            'pharmacy.show',
            compact('order')
        );
    }

    public function doctorOrders()
    {
        $this->ensureDoctor();

        $orders = PharmacyOrder::with([
            'patient',
            'items.medicine',
        ])
            ->where('status', 'pending')
            ->latest()
            ->paginate(15);

        return view(
            'pharmacy.doctor_orders',
            compact('orders')
        );
    }

    public function accept(PharmacyOrder $order)
    {
        $this->ensureDoctor();

        DB::transaction(function () use ($order) {

            /*
             * Lock the order first so two doctors cannot
             * process the same order simultaneously.
             */
            $order = PharmacyOrder::lockForUpdate()
                ->with('items')
                ->findOrFail($order->id);

            if ($order->status !== 'pending') {
                abort(
                    422,
                    'This order has already been processed.'
                );
            }

            /*
             * Lock every medicine before checking stock.
             */
            $medicines = [];

            foreach ($order->items as $item) {

                $medicine = Medicine::lockForUpdate()
                    ->findOrFail($item->medicine_id);

                if ($medicine->stock < $item->quantity) {
                    abort(
                        422,
                        "Not enough stock for {$medicine->name}."
                    );
                }

                $medicines[$medicine->id] = $medicine;
            }

            /*
             * Reduce stock only after all medicines have
             * passed the stock check.
             */
            foreach ($order->items as $item) {

                $medicine = $medicines[$item->medicine_id];

                $medicine->decrement(
                    'stock',
                    $item->quantity
                );
            }

            /*
             * Mark the order as accepted and record
             * which doctor accepted it.
             */
            $order->update([
                'status' => 'accepted',
                'accepted_by' => Auth::id(),
                'accepted_at' => now(),
            ]);
        });

        return back()->with(
            'success',
            'Pharmacy order accepted successfully.'
        );
    }
}