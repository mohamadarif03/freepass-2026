<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionRequest;
use App\Service\TransactionService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Exception;

class TransactionController extends Controller
{
    private TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function create(TransactionRequest $request)
    {
        try {
            $validated = $request->validated();

            $result = $this->transactionService->createTransaction($validated, Auth::user());

            return ResponseHelper::success($result, 'Transaction created successfully');
        } catch (ValidationException $e) {
            return ResponseHelper::error($e->errors(), 'Validation Error', 422);
        } catch (Exception $e) {
            return ResponseHelper::error(null, $e->getMessage(), 500);
        }
    }
}
