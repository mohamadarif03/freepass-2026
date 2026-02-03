<?php

namespace App\Http\Controllers\Api\Users;

use App\Contracts\Interfaces\CanteenInterface;
use App\Http\Controllers\Controller;
use App\Helpers\ResponseHelper;
use App\Http\Resources\CanteenResource;
use App\Traits\PaginationTrait;
use Illuminate\Http\Request;

class CanteenController extends Controller
{
    use PaginationTrait;
    private CanteenInterface $canteenInterface;

    public function __construct(CanteenInterface $canteenInterface)
    {
        $this->canteenInterface = $canteenInterface;
    }

    public function index(Request $request)
    {
        $canteens = $this->canteenInterface->customPaginate($request, 10);
        $data['paginate'] = $this->customPaginate($canteens->currentPage(), $canteens->lastPage());
        $data['data'] = CanteenResource::collection($canteens);
        return ResponseHelper::success($data, 'Canteens loaded successfully');
    }

}
