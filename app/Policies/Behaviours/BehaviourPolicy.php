<?php

namespace App\Policies\Behaviours;

use App\Models\Behaviours\Behaviour;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BehaviourPolicy
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
     * Determine whether the user can view the DocBehaviour.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Behaviours\Behaviour  $behaviour
     * @return mixed
     */
    public function view(User $user, Behaviour $behaviour)
    {
        return ($user->id == $behaviour->user_id);
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
     * Determine whether the user can update the DocBehaviour.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Behaviours\Behaviour  $behaviour
     * @return mixed
     */
    public function update(User $user, Behaviour $behaviour)
    {
        return ($user->id == $behaviour->user_id);
    }

    /**
     * Determine whether the user can delete the DocBehaviour.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Behaviours\Behaviour  $behaviour
     * @return mixed
     */
    public function delete(User $user, Behaviour $behaviour)
    {
        return ($user->id == $behaviour->user_id);
    }

    /**
     * Determine whether the user can restore the DocBehaviour.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Behaviours\Behaviour  $behaviour
     * @return mixed
     */
    public function restore(User $user, Behaviour $behaviour)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the DocBehaviour.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Behaviours\Behaviour  $behaviour
     * @return mixed
     */
    public function forceDelete(User $user, Behaviour $behaviour)
    {
        //
    }
}
