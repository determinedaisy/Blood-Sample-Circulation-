<?php

namespace App\Http\Controllers;

use App\Models\BloodSample;
use App\Models\Payment;
use App\Services\BkashService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    protected BkashService $bkashService;

    public function __construct(BkashService $bkashService)
    {
        $this->bkashService = $bkashService;
    }

    /**
     * Initiate payment.
     */
    public function initiate(Request $request, $sample_code)
    {
        // Find the patient's sample first.
        $sample = BloodSample::with('payment')
            ->where('sample_code', $sample_code)
            ->where('patient_id', Auth::id())
            ->firstOrFail();

        // Prevent duplicate payments.
        if ($sample->payment && $sample->payment->status === 'completed') {
            return redirect()
                ->back()
                ->with('error', 'This sample has already been paid for.');
        }

        $amount = 500.00;
        $invoiceNumber = 'INV-' . strtoupper(Str::random(10));

        /*
         * Create a pending demo payment locally, then show the mock checkout.
         */
        if (app()->environment('local')) {
            $payment = Payment::updateOrCreate(
                ['blood_sample_id' => $sample->id],
                [
                    'patient_id' => Auth::id(),
                    'amount' => $amount,
                    'invoice_number' => $invoiceNumber,
                    'bkash_payment_id' => 'MOCK-' . Str::uuid(),
                    'transaction_id' => null,
                    'status' => 'pending',
                ]
            );

            return redirect()->route('bkash.mock.checkout', $payment->id);
        }

        /*
         * Real bKash payment for non-local environments.
         */
        try {
            $response = $this->bkashService->createPayment(
                $amount,
                $invoiceNumber,
                route('bkash.callback')
            );

            if (isset($response['bkashURL'], $response['paymentID'])) {
                Payment::updateOrCreate(
                    ['blood_sample_id' => $sample->id],
                    [
                        'patient_id' => Auth::id(),
                        'amount' => $amount,
                        'invoice_number' => $invoiceNumber,
                        'bkash_payment_id' => $response['paymentID'],
                        'transaction_id' => null,
                        'status' => 'pending',
                    ]
                );

                return redirect()->away($response['bkashURL']);
            }

            return redirect()
                ->back()
                ->with('error', 'Gateway Error: Could not connect to bKash.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Payment Error: ' . $e->getMessage());
        }
    }

    /**
     * Show the local-only demo checkout page.
     */
    public function mockCheckout($paymentId)
    {
        abort_unless(app()->environment('local'), 404);

        $payment = Payment::where('id', $paymentId)
            ->where('patient_id', Auth::id())
            ->firstOrFail();

        if ($payment->status === 'completed') {
            return redirect()
                ->route('patient.blood-samples.index')
                ->with('error', 'This payment has already been completed.');
        }

        return view('payments.bkash-mock', compact('payment'));
    }

    /**
     * Complete a local-only demo payment.
     */
    public function mockConfirm(Request $request, $paymentId)
    {
        abort_unless(app()->environment('local'), 404);

        $request->validate([
            'test_number' => ['required', 'regex:/^01[0-9]{9}$/'],
            'test_pin' => ['required', 'digits_between:4,6'],
        ], [
            'test_number.regex' => 'Enter an 11-digit demo mobile number.',
            'test_pin.digits_between' => 'Enter a 4-6 digit demo PIN.',
        ]);

        $payment = Payment::where('id', $paymentId)
            ->where('patient_id', Auth::id())
            ->firstOrFail();

        if ($payment->status === 'completed') {
            return redirect()
                ->route('patient.blood-samples.index')
                ->with('error', 'This payment has already been completed.');
        }

        $transactionId = 'MOCK-' . strtoupper(Str::random(12));

        $payment->update([
            'transaction_id' => $transactionId,
            'status' => 'completed',
        ]);

        return redirect()
            ->route('patient.blood-samples.index')
            ->with('success', "Demo bKash payment successful! TrxID: {$transactionId}");
    }

    /**
     * Cancel a local-only demo payment.
     */
    public function mockCancel($paymentId)
    {
        abort_unless(app()->environment('local'), 404);

        $payment = Payment::where('id', $paymentId)
            ->where('patient_id', Auth::id())
            ->firstOrFail();

        if ($payment->status !== 'completed') {
            $payment->update(['status' => 'failed']);
        }

        return redirect()
            ->route('patient.blood-samples.index')
            ->with('error', 'Demo payment was cancelled.');
    }

    /**
     * Process the real bKash callback.
     */
    public function callback(Request $request)
    {
        $paymentID = $request->input('paymentID');
        $status = $request->input('status');

        $payment = Payment::where('bkash_payment_id', $paymentID)->first();

        if (!$payment) {
            return redirect()
                ->route('patient.blood-samples.index')
                ->with('error', 'Transaction record not found.');
        }

        if ($status === 'success') {
            try {
                $response = $this->bkashService->executePayment($paymentID);

                if (($response['statusCode'] ?? null) === '0000') {
                    $transactionId = $response['trxID'] ?? null;

                    $payment->update([
                        'transaction_id' => $transactionId,
                        'status' => 'completed',
                    ]);

                    return redirect()
                        ->route('patient.blood-samples.index')
                        ->with(
                            'success',
                            "Payment Successful! TrxID: {$transactionId}"
                        );
                }
            } catch (\Exception $e) {
                $payment->update(['status' => 'failed']);

                return redirect()
                    ->route('patient.blood-samples.index')
                    ->with('error', 'Payment execution failed.');
            }
        }

        $payment->update(['status' => 'failed']);

        return redirect()
            ->route('patient.blood-samples.index')
            ->with('error', 'Payment was canceled or failed authorization.');
    }
}