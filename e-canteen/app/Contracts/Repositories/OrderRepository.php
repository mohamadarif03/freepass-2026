<?php

namespace App\Contracts\Repositories;

use App\Contracts\Interfaces\OrderInterface;
use App\Enums\RoleEnum;
use App\Helpers\UserHelper;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class OrderRepository extends BaseRepository implements OrderInterface
{

    public function __construct(Transaction $transaction)
    {
        $this->model = $transaction;
    }

    public function customPaginate(Request $request, int $pagination = 10): LengthAwarePaginator
    {
        return $this->model->query()
            ->when($request->name, function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->name . '%');
            })
            ->when(UserHelper::getUserRole() == RoleEnum::USER->value, function ($query) use ($request) {
                $query->where('user_id', Auth::id());
            })
            ->when(UserHelper::getUserRole() == RoleEnum::CANTEEN->value, function ($query) use ($request) {
                $query->whereRelation('menus.menu.canteen', 'user_id', Auth::id());
            })
            ->paginate($pagination);
    }
}
