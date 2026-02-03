<?php

namespace App\Service;

use App\Contracts\Interfaces\CanteenOwnerInterface;
use App\Enums\RoleEnum;
use App\Http\Requests\CanteenOwnerRequest;
use Illuminate\Support\Facades\DB;

class CanteenOwnerService
{
    public function handleStore(CanteenOwnerRequest $request, CanteenOwnerInterface $canteenOwner): mixed
    {
        return DB::transaction(function () use ($request, $canteenOwner) {
            $data = $request->validated();
            $data['password'] = bcrypt('password');
            $data['role'] = RoleEnum::CANTEEN->value;

            $user = $canteenOwner->store($data);

            $user->canteen()->create([
                'name' => $data['canteen_name'],
                'location' => $data['canteen_location'],
            ]);

            return $user;
        });
    }

    public function handleUpdate(CanteenOwnerRequest $request, CanteenOwnerInterface $canteenOwner, $id): mixed
    {
        return DB::transaction(function () use ($request, $canteenOwner, $id) {
            $data = $request->validated();

            $user = $canteenOwner->getById($id);

            $canteenOwner->update($id, $data);

            $user->canteen()->update([
                'name' => $data['canteen_name'],
                'location' => $data['canteen_location'],
            ]);

            return $user->fresh(['canteen']);
        });
    }
}
