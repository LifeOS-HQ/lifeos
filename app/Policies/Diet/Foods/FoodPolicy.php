<?php

namespace App\Policies\Diet\Foods;

use App\Models\Diet\Foods\Food;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FoodPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any DocDummyPluralModel.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the DocFood.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Diet\Foods\Food  $food
     * @return mixed
     */
    public function view(User $user, Food $food)
    {
        return ($user->id == $food->user_id);
    }

    /**
     * Determine whether the user can create DocDummyPluralModel.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the DocFood.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Diet\Foods\Food  $food
     * @return mixed
     */
    public function update(User $user, Food $food)
    {
        return ($user->id == $food->user_id);
    }

    /**
     * Determine whether the user can delete the DocFood.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Diet\Foods\Food  $food
     * @return mixed
     */
    public function delete(User $user, Food $food)
    {
        return ($user->id == $food->user_id);
    }

    /**
     * Determine whether the user can restore the DocFood.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Diet\Foods\Food  $food
     * @return mixed
     */
    public function restore(User $user, Food $food)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the DocFood.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Diet\Foods\Food  $food
     * @return mixed
     */
    public function forceDelete(User $user, Food $food)
    {
        //
    }
}
