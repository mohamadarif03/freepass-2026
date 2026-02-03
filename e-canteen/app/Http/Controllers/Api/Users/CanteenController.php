<?php

namespace App\Http\Controllers\Api\Users;

use App\Contracts\Interfaces\CanteenInterface;
use App\Contracts\Interfaces\MenuInterface;
use App\Http\Controllers\Controller;
use App\Helpers\ResponseHelper;
use App\Http\Resources\CanteenResource;
use App\Http\Resources\MenuResource;
use App\Models\Canteen;
use App\Traits\PaginationTrait;
use Illuminate\Http\Request;

class CanteenController extends Controller
{
    use PaginationTrait;
    private CanteenInterface $canteenInterface;
    private MenuInterface $menuInterface;

    public function __construct(CanteenInterface $canteenInterface, MenuInterface $menuInterface)
    {
        $this->canteenInterface = $canteenInterface;
        $this->menuInterface = $menuInterface;
    }

    public function index(Request $request)
    {
        $canteens = $this->canteenInterface->customPaginate($request, 10);
        $data['paginate'] = $this->customPaginate($canteens->currentPage(), $canteens->lastPage());
        $data['data'] = CanteenResource::collection($canteens);
        return ResponseHelper::success($data, 'Canteens loaded successfully');
    }

    public function show(Request $request, Canteen $canteen)
    {
        $menus = $this->menuInterface->getByCanteenId($request, $canteen->id);
        $data['paginate'] = $this->customPaginate($menus->currentPage(), $menus->lastPage());
        $data['data'] = MenuResource::collection($menus);
        return ResponseHelper::success($data, 'Canteen loaded successfully');
    }
}
