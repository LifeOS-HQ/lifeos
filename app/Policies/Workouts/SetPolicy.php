<?php

namespace App\Policies\Workouts;

use App\Models\Workouts\Set;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SetPolicy
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
     * Determine whether the user can view the DocSet.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Workouts\Set  $set
     * @return mixed
     */
    public function view(User $user, Set $set)
    {
        return ($user->id == $set->user_id);
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
     * Determine whether the user can update the DocSet.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Workouts\Set  $set
     * @return mixed
     */
    public function update(User $user, Set $set)
    {
        return ($user->id == $set->user_id);
    }

    /**
     * Determine whether the user can delete the DocSet.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Workouts\Set  $set
     * @return mixed
     */
    public function delete(User $user, Set $set)
    {
        return ($user->id == $set->user_id);
    }

    /**
     * Determine whether the user can restore the DocSet.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Workouts\Set  $set
     * @return mixed
     */
    public function restore(User $user, Set $set)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the DocSet.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Workouts\Set  $set
     * @return mixed
     */
    public function forceDelete(User $user, Set $set)
    {
        //
    }
}
