<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Interfaces\OrderInterface;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
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
}
