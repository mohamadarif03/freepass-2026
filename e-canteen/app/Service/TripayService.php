<?php

namespace App\Service;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class TripayService
{
    private string $baseUrl;
    private string $apiKey;

    public function __construct()
    {
        $this->baseUrl = env('TRIPAY_API_URL');
        $this->apiKey = env('TRIPAY_API_KEY');
    }

    public function getPaymentChannels(): array|null
    {
        return Cache::remember('tripay_channels', 60 * 60 * 24, function () {
            $response = Http::withToken($this->apiKey)
                ->get($this->baseUrl . '/payment-channel');

            if ($response->json('success')) {
                return $response->json('data');
            }

            return null;
        });
    }
}
