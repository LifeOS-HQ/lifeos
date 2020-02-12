<?php

namespace App\Policies\Lifeareas;

use App\Models\Lifeareas\Lifearea;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LifeareaPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any lifeareas.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the lifearea.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Lifeareas\Lifearea  $lifearea
     * @return mixed
     */
    public function view(User $user, Lifearea $lifearea)
    {
        return ($user->id == $lifearea->user_id);
    }

    /**
     * Determine whether the user can create lifeareas.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the lifearea.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Lifeareas\Lifearea  $lifearea
     * @return mixed
     */
    public function update(User $user, Lifearea $lifearea)
    {
        return ($user->id == $lifearea->user_id);
    }

    /**
     * Determine whether the user can delete the lifearea.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Lifeareas\Lifearea  $lifearea
     * @return mixed
     */
    public function delete(User $user, Lifearea $lifearea)
    {
        return ($user->id == $lifearea->user_id);
    }

    /**
     * Determine whether the user can restore the lifearea.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Lifeareas\Lifearea  $lifearea
     * @return mixed
     */
    public function restore(User $user, Lifearea $lifearea)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the lifearea.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Lifeareas\Lifearea  $lifearea
     * @return mixed
     */
    public function forceDelete(User $user, Lifearea $lifearea)
    {
        //
    }
}
