<?php

namespace App\Policies\Days;

use App\Models\Days\Day;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DayPolicy
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
     * Determine whether the user can view the DocDay.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Days\Day  $day
     * @return mixed
     */
    public function view(User $user, Day $day)
    {
        return ($user->id == $day->user_id);
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
     * Determine whether the user can update the DocDay.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Days\Day  $day
     * @return mixed
     */
    public function update(User $user, Day $day)
    {
        return ($user->id == $day->user_id);
    }

    /**
     * Determine whether the user can delete the DocDay.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Days\Day  $day
     * @return mixed
     */
    public function delete(User $user, Day $day)
    {
        return ($user->id == $day->user_id);
    }

    /**
     * Determine whether the user can restore the DocDay.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Days\Day  $day
     * @return mixed
     */
    public function restore(User $user, Day $day)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the DocDay.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Days\Day  $day
     * @return mixed
     */
    public function forceDelete(User $user, Day $day)
    {
        //
    }
}
