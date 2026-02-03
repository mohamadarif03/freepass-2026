<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Service\TripayService;

class CheckoutController extends Controller
{
    private TripayService $tripayService;

    public function __construct(TripayService $tripayService)
    {
        $this->tripayService = $tripayService;
    }

    public function index()
    {
        $channels = $this->tripayService->getPaymentChannels();

        if ($channels === null) {
            return ResponseHelper::error('Failed to get payment channel data');
        }
        return ResponseHelper::success($channels);
    }
}
