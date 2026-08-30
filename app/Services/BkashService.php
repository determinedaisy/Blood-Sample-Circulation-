<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Exception;

class BkashService
{
    protected string $baseUrl;
    protected string $appKey;
    protected string $appSecret;
    protected string $username;
    protected string $password;

    public function __construct()
    {
        $this->baseUrl = config('services.bkash.base_url');
        $this->appKey = config('services.bkash.app_key');
        $this->appSecret = config('services.bkash.app_secret');
        $this->username = config('services.bkash.username');
        $this->password = config('services.bkash.password');
    }

    /**
     * Authenticate with bKash and retrieve a token.
     */
    public function getToken(): string
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'username' => $this->username,
            'password' => $this->password,
        ])->post("{$this->baseUrl}/tokenized/checkout/token/grant", [
            'app_key' => $this->appKey,
            'app_secret' => $this->appSecret,
        ]);

        if ($response->successful() && isset($response->json()['id_token'])) {
            return $response->json()['id_token'];
        }

        throw new Exception('bKash Authentication Failed: ' . $response->body());
    }

    /**
     * Generate the payment URL and Invoice.
     */
    public function createPayment(float $amount, string $invoiceNumber, string $callbackUrl): array
    {
        $token = $this->getToken();

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'authorization' => $token,
            'x-app-key' => $this->appKey,
        ])->post("{$this->baseUrl}/tokenized/checkout/create", [
            'mode' => '0011',
            'payerReference' => 'SampleFee',
            'callbackURL' => $callbackUrl,
            'amount' => $amount,
            'currency' => 'BDT',
            'intent' => 'sale',
            'merchantInvoiceNumber' => $invoiceNumber,
        ]);

        return $response->json();
    }

    /**
     * Finalize and capture the funds after user enters OTP/PIN.
     */
    public function executePayment(string $paymentID): array
    {
        $token = $this->getToken();

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'authorization' => $token,
            'x-app-key' => $this->appKey,
        ])->post("{$this->baseUrl}/tokenized/checkout/execute", [
            'paymentID' => $paymentID,
        ]);

        return $response->json();
    }
}