<?php

namespace App\Http\Controllers\Auth;

use App\Contracts\Interfaces\CanteenOwnerInterface;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\CanteenOwnerResource;
use App\Traits\PaginationTrait;
use Illuminate\Http\Request;

class CanteenOwnerController extends Controller
{
    use PaginationTrait;
    private CanteenOwnerInterface $canteenOwner;

    public function __construct(CanteenOwnerInterface $canteenOwner)
    {
        $this->canteenOwner = $canteenOwner;
    }

    public function index(Request $request)
    {
        $canteenOwners = $this->canteenOwner->customPaginate($request, 10);
        $data['paginate'] = $this->customPaginate($canteenOwners->currentPage(), $canteenOwners->lastPage());
        $data['data'] = CanteenOwnerResource::collection($canteenOwners);
        return ResponseHelper::success($data, 'Canteen owner loaded successfully');
    }


}
