<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Interfaces\ReviewInterface;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Review;
use App\Traits\PaginationTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    use PaginationTrait;
    private ReviewInterface $reviewInterface;

    public function __construct(ReviewInterface $reviewInterface)
    {
        $this->reviewInterface = $reviewInterface;
    }

    public function index(Request $request) {
        $reviews = $this->reviewInterface->customPaginate($request);
        $data['paginate'] = $this->customPaginate($reviews->currentPage(), $reviews->lastPage());
        $data['data'] = ReviewResource::collection($reviews);
        return ResponseHelper::success($data, 'Reviews retrieved successfully');
    }


    public function store(StoreReviewRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();

        $review = $this->reviewInterface->store($data);
        return ResponseHelper::success(ReviewResource::make($review), 'Review created successfully');
    }

    public function update(UpdateReviewRequest $request, Review $review)
    {
        $this->authorize('update', $review);
        $data = $request->validated();
        $this->reviewInterface->update($review->id, $data);
        return ResponseHelper::success(ReviewResource::make($review->refresh()), 'Review updated successfully');
    }

    public function destroy(Review $review)
    {
        $this->authorize('delete', $review);
        $this->reviewInterface->delete($review->id);
        return ResponseHelper::success(null, 'Review deleted successfully');
    }
}
