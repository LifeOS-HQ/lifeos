<?php

namespace App\Policies\Diet\Diary\Meals;

use App\Models\Diet\Diary\Meals\Meal;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MealPolicy
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
     * Determine whether the user can view the DocMeal.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Diet\Diary\Meals\Meal  $meal
     * @return mixed
     */
    public function view(User $user, Meal $meal)
    {
        return ($user->id == $meal->user_id);
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
     * Determine whether the user can update the DocMeal.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Diet\Diary\Meals\Meal  $meal
     * @return mixed
     */
    public function update(User $user, Meal $meal)
    {
        return ($user->id == $meal->user_id);
    }

    /**
     * Determine whether the user can delete the DocMeal.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Diet\Diary\Meals\Meal  $meal
     * @return mixed
     */
    public function delete(User $user, Meal $meal)
    {
        return ($user->id == $meal->user_id);
    }

    /**
     * Determine whether the user can restore the DocMeal.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Diet\Diary\Meals\Meal  $meal
     * @return mixed
     */
    public function restore(User $user, Meal $meal)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the DocMeal.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Diet\Diary\Meals\Meal  $meal
     * @return mixed
     */
    public function forceDelete(User $user, Meal $meal)
    {
        //
    }
}
