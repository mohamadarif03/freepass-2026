<?php

namespace App\Contracts\Repositories;

use App\Contracts\Interfaces\CanteenInterface;
use App\Contracts\Repositories\BaseRepository;
use App\Models\Canteen;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class CanteenRepository extends BaseRepository implements CanteenInterface

{
    public function __construct(Canteen $canteen)
    {
        $this->model = $canteen;
    }

    public function customPaginate(Request $request, int $pagination = 10): LengthAwarePaginator
    {
        return $this->model->query()
            ->when($request->name, function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->name . '%');
            })
            ->paginate($pagination);
    }
}
