<?php

namespace App\Http\Controllers\Api;


use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Service\TransactionService;
use App\Service\TripayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CallbackController extends Controller
{
    private TripayService $tripayService;
    private TransactionService $transactionService;

    public function __construct(TripayService $tripayService, TransactionService $transactionService)
    {
        $this->tripayService = $tripayService;
        $this->transactionService = $transactionService;
    }

    public function handle(Request $request)
    {
        $result = $this->tripayService->handleCallback($request);

        if (!$result['success']) {
            return response()->json(['success' => false, 'message' => $result['message']], 400);
        }

        $processResult = $this->transactionService->handleCallback($result);

        if (!$processResult['success']) {
            return response()->json(['success' => false, 'message' => $processResult['message']], 400);
        }

        return response()->json(['success' => true]);
    }
}
