<?php

namespace App\Http\Controllers\Auth;

use App\Contracts\Interfaces\CanteenOwnerInterface;
use App\Enums\RoleEnum;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\CanteenOwnerRequest;
use App\Http\Resources\CanteenOwnerResource;
use App\Models\User;
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

    public function store(CanteenOwnerRequest $request)
    {
        $data = $request->validated();
        $data['password'] = bcrypt('password');
        $data['role'] = RoleEnum::CANTEEN->value;
        $canteenOwner = $this->canteenOwner->store($data);
        return ResponseHelper::success(CanteenOwnerResource::make($canteenOwner), 'Canteen owner created successfully');
    }

    public function update(CanteenOwnerRequest $request, User $user)
    {
        $data = $request->validated();
        $this->canteenOwner->update($user->id, $data);
        return ResponseHelper::success(CanteenOwnerResource::make($user->refresh()), 'Canteen owner updated successfully');
    }

    public function destroy(User $user)
    {
        $this->canteenOwner->delete($user->id);
        return ResponseHelper::success(null, 'Canteen owner deleted successfully');
    }
}
