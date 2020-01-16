<?php

namespace App\Policies\Work;

use App\Models\Work\Month;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MonthPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any months.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the month.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Work\Month  $month
     * @return mixed
     */
    public function view(User $user, Month $month)
    {
        return ($user->id == $month->user_id);
    }

    /**
     * Determine whether the user can create months.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the month.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Work\Month  $month
     * @return mixed
     */
    public function update(User $user, Month $month)
    {
        return ($user->id == $month->user_id);
    }

    /**
     * Determine whether the user can delete the month.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Work\Month  $month
     * @return mixed
     */
    public function delete(User $user, Month $month)
    {
        return ($user->id == $month->user_id);
    }

    /**
     * Determine whether the user can restore the month.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Work\Month  $month
     * @return mixed
     */
    public function restore(User $user, Month $month)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the month.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Work\Month  $month
     * @return mixed
     */
    public function forceDelete(User $user, Month $month)
    {
        //
    }
}
