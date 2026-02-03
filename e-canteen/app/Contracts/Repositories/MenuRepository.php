<?php

namespace App\Contracts\Repositories;

use App\Contracts\Interfaces\MenuInterface;
use App\Contracts\Repositories\BaseRepository;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class MenuRepository extends BaseRepository implements MenuInterface
{
    public function __construct(Menu $menu)
    {
        $this->model = $menu;
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
            ->where('user_id', Auth::id())
            ->paginate($pagination);
    }

    public function getByCanteenId(Request $request, int $canteenId): mixed
    {
        return $this->model->query()
            ->when($request->name, function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->name . '%');
            })
            ->where('canteen_id', $canteenId)
            ->paginate(10);
    }

    public function delete(mixed $id): mixed
    {
        return $this->model->findOrFail($id)->delete();
    }
}
