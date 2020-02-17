<?php

namespace App\Policies\Lifeareas;

use App\Models\Lifeareas\Scale;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ScalePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any scales.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the scale.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Lifeareas\Scale  $scale
     * @return mixed
     */
    public function view(User $user, Scale $scale)
    {
        return ($user->id == $scale->user_id);
    }

    /**
     * Determine whether the user can create scales.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the scale.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Lifeareas\Scale  $scale
     * @return mixed
     */
    public function update(User $user, Scale $scale)
    {
        return ($user->id == $scale->user_id);
    }

    /**
     * Determine whether the user can delete the scale.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Lifeareas\Scale  $scale
     * @return mixed
     */
    public function delete(User $user, Scale $scale)
    {
        return ($user->id == $scale->user_id);
    }

    /**
     * Determine whether the user can restore the scale.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Lifeareas\Scale  $scale
     * @return mixed
     */
    public function restore(User $user, Scale $scale)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the scale.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Lifeareas\Scale  $scale
     * @return mixed
     */
    public function forceDelete(User $user, Scale $scale)
    {
        //
    }
}
