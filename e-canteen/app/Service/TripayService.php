<?php

namespace App\Service;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class TripayService
{
    private string $baseUrl;
    private string $apiKey;
    private string $privateKey;
    private string $merchantCode;

    public function __construct()
    {
        $this->baseUrl = env('TRIPAY_API_URL');
        $this->apiKey = env('TRIPAY_API_KEY');
        $this->privateKey = env('TRIPAY_PRIVATE_KEY');
        $this->merchantCode = env('TRIPAY_MERCHANT_CODE');
    }

    public function getPaymentChannels(): array
    {
        $response = Http::withToken($this->apiKey)
            ->get($this->baseUrl . '/merchant/payment-channel');

        if ($response->json('success')) {
            return $response->json('data');
        }

        return [];
    }

    public function requestTransaction($method, $transaction, $orderItems, $user)
    {
        $merchantRef = 'INV-' . time();
        $amount = $transaction->total_amount;

        $signature = hash_hmac('sha256', $this->merchantCode . $merchantRef . $amount, $this->privateKey);

        $payload = [
            'method'         => $method,
            'merchant_ref'   => $merchantRef,
            'amount'         => $amount,
            'customer_name'  => $user->name,
            'customer_email' => $user->email,
            'customer_phone' => $user->phone_number ?? '081234567890',
            'order_items'    => $orderItems,
            'return_url'     => 'https://google.com',
            'expired_time'   => (time() + (24 * 60 * 60)),
            'signature'      => $signature
        ];

        $response = Http::withToken($this->apiKey)
            ->post($this->baseUrl . '/transaction/create', $payload);

        return $response->json();
    }
}
