<?php

namespace App\Contracts\Repositories;

use App\Contracts\Interfaces\CanteenOwnerInterface;
use App\Contracts\Repositories\BaseRepository;
use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class CanteenOwnerRepository extends BaseRepository implements CanteenOwnerInterface

{
    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function getById(mixed $id): mixed
    {
        return $this->model->findOrFail($id);
    }

    public function store(array $data): mixed
    {
        return $this->model->create($data);
    }

    public function update(mixed $id, array $data): mixed
    {
        return $this->model->findOrFail($id)->update($data);
    }

    public function customPaginate(Request $request, int $pagination = 10): LengthAwarePaginator
    {
        return $this->model->query()
            ->when($request->name, function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->name . '%');
            })
            ->where('role', RoleEnum::CANTEEN->value)
            ->paginate($pagination);
    }

    public function delete(mixed $id): mixed
    {
        return $this->model->findOrFail($id)->delete();
    }
}