<?php

namespace App\Policies\Behaviours;

use App\Models\Behaviours\History;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class HistoryPolicy
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
     * Determine whether the user can view the DocHistory.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Behaviours\History  $history
     * @return mixed
     */
    public function view(User $user, History $history)
    {
        return ($user->id == $history->user_id);
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
     * Determine whether the user can update the DocHistory.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Behaviours\History  $history
     * @return mixed
     */
    public function update(User $user, History $history)
    {
        return ($user->id == $history->user_id);
    }

    /**
     * Determine whether the user can delete the DocHistory.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Behaviours\History  $history
     * @return mixed
     */
    public function delete(User $user, History $history)
    {
        return ($user->id == $history->user_id);
    }

    /**
     * Determine whether the user can restore the DocHistory.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Behaviours\History  $history
     * @return mixed
     */
    public function restore(User $user, History $history)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the DocHistory.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Behaviours\History  $history
     * @return mixed
     */
    public function forceDelete(User $user, History $history)
    {
        //
    }
}
