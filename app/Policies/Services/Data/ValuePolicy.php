<?php

namespace App\Policies\Services\Data;

use App\Models\Services\Data\Value;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ValuePolicy
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
     * Determine whether the user can view the DocValue.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Services\Data\Value  $value
     * @return mixed
     */
    public function view(User $user, Value $value)
    {
        return ($user->id == $value->user_id);
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
     * Determine whether the user can update the DocValue.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Services\Data\Value  $value
     * @return mixed
     */
    public function update(User $user, Value $value)
    {
        return ($user->id == $value->user_id);
    }

    /**
     * Determine whether the user can delete the DocValue.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Services\Data\Value  $value
     * @return mixed
     */
    public function delete(User $user, Value $value)
    {
        return ($user->id == $value->user_id);
    }

    /**
     * Determine whether the user can restore the DocValue.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Services\Data\Value  $value
     * @return mixed
     */
    public function restore(User $user, Value $value)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the DocValue.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Services\Data\Value  $value
     * @return mixed
     */
    public function forceDelete(User $user, Value $value)
    {
        //
    }
}
