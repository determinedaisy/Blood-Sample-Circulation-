<?php

namespace App\Http\Controllers;

use App\Models\BloodSample;
use App\Models\Payment;
use App\Services\BkashService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    protected BkashService $bkashService;

    public function __construct(BkashService $bkashService)
    {
        $this->bkashService = $bkashService;
    }

    /**
     * Initiate the bKash Gateway Popup.
     */
    public function initiate(Request $request, $sample_code)
    {
        $sample = BloodSample::with('payment')
            ->where('sample_code', $sample_code)
            ->where('patient_id', auth()->id())
            ->firstOrFail();

        // Security Check: Prevent duplicate payments
        if ($sample->payment && $sample->payment->status === 'completed') {
            return redirect()->back()->with('error', 'This sample has already been paid for.');
        }

        $amount = 500.00; // Standard testing fee
        $invoiceNumber = 'INV-' . strtoupper(Str::random(10));

        try {
            $response = $this->bkashService->createPayment(
                $amount, 
                $invoiceNumber, 
                route('bkash.callback')
            );

            if (isset($response['bkashURL'])) {
                // Update existing pending payment or create a new one
                Payment::updateOrCreate(
                    ['blood_sample_id' => $sample->id],
                    [
                        'patient_id' => auth()->id(),
                        'amount' => $amount,
                        'invoice_number' => $invoiceNumber,
                        'bkash_payment_id' => $response['paymentID'],
                        'status' => 'pending',
                    ]
                );

                return redirect()->intended($response['bkashURL']);
            }

            return redirect()->back()->with('error', 'Gateway Error: Could not connect to bKash.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Webhook/Callback for bKash to ping after user completes transaction.
     */
    public function callback(Request $request)
    {
        $paymentID = $request->input('paymentID');
        $status = $request->input('status');

        $payment = Payment::where('bkash_payment_id', $paymentID)->first();

        if (!$payment) {
            return redirect()->route('patient.blood-samples.index')->with('error', 'Transaction record not found.');
        }

        if ($status === 'success') {
            $response = $this->bkashService->executePayment($paymentID);

            if (isset($response['statusCode']) && $response['statusCode'] === '0000') {
                $payment->update([
                    'transaction_id' => $response['trxID'] ?? null,
                    'status' => 'completed',
                ]);

                return redirect()->route('patient.blood-samples.index')->with('success', "Payment Successful! TrxID: {$response['trxID']}");
            }
        }

        $payment->update(['status' => 'failed']);
        return redirect()->route('patient.blood-samples.index')->with('error', 'Payment was canceled or failed authorization.');
    }
}