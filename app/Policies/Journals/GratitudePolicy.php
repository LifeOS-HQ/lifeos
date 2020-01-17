<?php

namespace App\Policies\Journals;

use App\Models\Journals\Gratitude\Gratitude;
use App\Models\Journals\Journal;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GratitudePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any gratitudes.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the gratitude.
     *
     * @param  \App\User  $user
     * @param  \App\Model\Journals\Gratitude\Gratitude  $gratitude
     * @return mixed
     */
    public function view(User $user, Gratitude $gratitude)
    {
        return ($user->id == $gratitude->user_id);
    }

    /**
     * Determine whether the user can create gratitudes.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the gratitude.
     *
     * @param  \App\User  $user
     * @param  \App\Model\Journals\Gratitude\Gratitude  $gratitude
     * @return mixed
     */
    public function update(User $user, Gratitude $gratitude)
    {
        return ($user->id == $gratitude->user_id);
    }

    /**
     * Determine whether the user can delete the gratitude.
     *
     * @param  \App\User  $user
     * @param  \App\Model\Journals\Gratitude\Gratitude  $gratitude
     * @return mixed
     */
    public function delete(User $user, Gratitude $gratitude)
    {
        return ($user->id == $gratitude->user_id);
    }

    /**
     * Determine whether the user can restore the gratitude.
     *
     * @param  \App\User  $user
     * @param  \App\Model\Journals\Gratitude\Gratitude  $gratitude
     * @return mixed
     */
    public function restore(User $user, Gratitude $gratitude)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the gratitude.
     *
     * @param  \App\User  $user
     * @param  \App\Model\Journals\Gratitude\Gratitude  $gratitude
     * @return mixed
     */
    public function forceDelete(User $user, Gratitude $gratitude)
    {
        //
    }
}
