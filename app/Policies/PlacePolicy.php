<?php

namespace App\Policies;

use App\Models\Places\Place;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PlacePolicy
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
     * Determine whether the user can view the DocPlace.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Places\Place  $place
     * @return mixed
     */
    public function view(User $user, Place $place)
    {
        return ($user->id == $place->user_id);
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
     * Determine whether the user can update the DocPlace.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Places\Place  $place
     * @return mixed
     */
    public function update(User $user, Place $place)
    {
        return ($user->id == $place->user_id);
    }

    /**
     * Determine whether the user can delete the DocPlace.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Places\Place  $place
     * @return mixed
     */
    public function delete(User $user, Place $place)
    {
        return ($user->id == $place->user_id);
    }

    /**
     * Determine whether the user can restore the DocPlace.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Places\Place  $place
     * @return mixed
     */
    public function restore(User $user, Place $place)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the DocPlace.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Places\Place  $place
     * @return mixed
     */
    public function forceDelete(User $user, Place $place)
    {
        //
    }
}
