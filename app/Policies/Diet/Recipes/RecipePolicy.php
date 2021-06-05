<?php

namespace App\Policies\Diet\Recipes;

use App\Models\Diet\Recipes\Recipe;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RecipePolicy
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
     * Determine whether the user can view the DocRecipe.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Diet\Recipes\Recipe  $recipe
     * @return mixed
     */
    public function view(User $user, Recipe $recipe)
    {
        return ($user->id == $recipe->user_id);
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
     * Determine whether the user can update the DocRecipe.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Diet\Recipes\Recipe  $recipe
     * @return mixed
     */
    public function update(User $user, Recipe $recipe)
    {
        return ($user->id == $recipe->user_id);
    }

    /**
     * Determine whether the user can delete the DocRecipe.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Diet\Recipes\Recipe  $recipe
     * @return mixed
     */
    public function delete(User $user, Recipe $recipe)
    {
        return ($user->id == $recipe->user_id);
    }

    /**
     * Determine whether the user can restore the DocRecipe.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Diet\Recipes\Recipe  $recipe
     * @return mixed
     */
    public function restore(User $user, Recipe $recipe)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the DocRecipe.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Diet\Recipes\Recipe  $recipe
     * @return mixed
     */
    public function forceDelete(User $user, Recipe $recipe)
    {
        //
    }
}
