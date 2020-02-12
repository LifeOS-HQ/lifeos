<?php

namespace App\Policies\Reviews;

use App\Models\Reviews\Review;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReviewPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any reviews.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user, Review $review = null)
    {
        if (is_null($review)) {
            return true;
        }

        return ($user->id == $review->user_id);
    }

    /**
     * Determine whether the user can view the review.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Reviews\Review  $review
     * @return mixed
     */
    public function view(User $user, Review $review)
    {
        return ($user->id == $review->user_id);
    }

    /**
     * Determine whether the user can create reviews.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the review.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Reviews\Review  $review
     * @return mixed
     */
    public function update(User $user, Review $review)
    {
        return ($user->id == $review->user_id);
    }

    /**
     * Determine whether the user can delete the review.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Reviews\Review  $review
     * @return mixed
     */
    public function delete(User $user, Review $review)
    {
        return ($user->id == $review->user_id);
    }

    /**
     * Determine whether the user can restore the review.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Reviews\Review  $review
     * @return mixed
     */
    public function restore(User $user, Review $review)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the review.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Reviews\Review  $review
     * @return mixed
     */
    public function forceDelete(User $user, Review $review)
    {
        //
    }
}
