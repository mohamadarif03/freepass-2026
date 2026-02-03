<?php

namespace App\Contracts\Repositories;

use App\Contracts\Interfaces\ReviewInterface;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class ReviewRepository extends BaseRepository implements ReviewInterface
{
    public function __construct(Review $review)
    {
        $this->model = $review;
    }

    public function customPaginate(Request $request, int $pagination = 10): LengthAwarePaginator
    {
        return $this->model->query()
            ->when($request->canteen_id, function ($query) use ($request) {
                $query->where('canteen_id', $request->canteen_id);
            })
            ->orderByRaw('user_id = ? DESC', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate($pagination);
    }

    public function store(array $data): mixed
    {
        return $this->model->create($data);
    }

    public function update(mixed $id, array $data): mixed
    {
        return $this->model->find($id)->update($data);
    }

    public function delete(mixed $id): mixed
    {
        return $this->model->find($id)->delete();
    }
}
