<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Interfaces\OrderInterface;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateStatusOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Transaction;
use App\Traits\PaginationTrait;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use PaginationTrait;
    private OrderInterface $orderInterface;

    public function __construct(OrderInterface $orderInterface)
    {
        $this->orderInterface = $orderInterface;
    }

    public function index(Request $request)
    {
        $orders = $this->orderInterface->customPaginate($request);
        $data['paginate'] = $this->customPaginate($orders->currentPage(), $orders->lastPage());
        $data['data'] = OrderResource::collection($orders);
        return ResponseHelper::success($data, 'Orders loaded successfully');
    }

    public function updateStatusPayment(Transaction $transaction)
    {
        $data = [
            'status_payment' => 'paid'
        ];
        $order = $this->orderInterface->update($transaction->id, $data);
        return ResponseHelper::success(OrderResource::make($transaction->refresh()), 'Status Payment Order updated successfully');
    }

    public function updateStatus(UpdateStatusOrderRequest $request, Transaction $transaction) {
        $data = [
            'status' => $request->status
        ];
        $order = $this->orderInterface->update($transaction->id, $data);
        return ResponseHelper::success(OrderResource::make($transaction->refresh()), 'Status Order updated successfully');
    }
}
