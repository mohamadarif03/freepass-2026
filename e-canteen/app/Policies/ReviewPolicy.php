<?php

namespace App\Policies;

use App\Models\Review;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ReviewPolicy
{

    public function update(User $user, Review $review): bool
    {
        return $user->id === $review->user_id;
    }
  
    public function delete(User $user, Review $review): bool
    {
        return $user->id === $review->user_id ||($user->role === 'canteen' && $user->canteen->id === $review->canteen_id);
    }


}
