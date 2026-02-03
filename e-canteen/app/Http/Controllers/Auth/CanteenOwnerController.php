<?php

namespace App\Http\Controllers\Auth;

use App\Contracts\Interfaces\CanteenInterface;
use App\Contracts\Interfaces\CanteenOwnerInterface;
use App\Enums\RoleEnum;
use App\Helpers\ResponseHelper;
use App\Helpers\UserHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\CanteenOwnerRequest;
use App\Http\Resources\CanteenOwnerResource;
use App\Models\User;
use App\Service\CanteenOwnerService;
use App\Traits\PaginationTrait;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;

class CanteenOwnerController extends Controller
{
    use PaginationTrait;
    private CanteenOwnerInterface $canteenOwner;
    private CanteenOwnerService $canteenOwnerService;

    public function __construct(CanteenOwnerInterface $canteenOwner, CanteenOwnerService $canteenOwnerService)
    {
        $this->canteenOwner = $canteenOwner;
        $this->canteenOwnerService = $canteenOwnerService;
    }

    public function index(Request $request)
    {
        $canteenOwners = $this->canteenOwner->customPaginate($request, 10);
        $data['paginate'] = $this->customPaginate($canteenOwners->currentPage(), $canteenOwners->lastPage());
        $data['data'] = CanteenOwnerResource::collection($canteenOwners);
        return ResponseHelper::success($data, 'Canteen owner loaded successfully');
    }

    public function store(CanteenOwnerRequest $request)
    {
        $canteenOwner = $this->canteenOwnerService->handleStore($request, $this->canteenOwner);

        return ResponseHelper::success(CanteenOwnerResource::make($canteenOwner), 'Canteen owner created successfully');
    }

    public function update(CanteenOwnerRequest $request, User $user)
    {
       $canteenOwner = $this->canteenOwnerService->handleUpdate($request, $this->canteenOwner, $user->id);
        return ResponseHelper::success(CanteenOwnerResource::make($canteenOwner), 'Canteen owner updated successfully');
    }

    public function destroy(User $user)
    {
        $this->canteenOwner->delete($user->id);
        $user->canteen()->delete();
        return ResponseHelper::success(null, 'Canteen owner deleted successfully');
    }
}
