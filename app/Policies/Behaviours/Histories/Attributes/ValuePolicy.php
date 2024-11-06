<?php

namespace App\Policies\Behaviours\Histories\Attributes;

use App\User;
use App\Models\Behaviours\History;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Behaviours\Histories\Attributes\Value;

class ValuePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any DocDummyPluralModel.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user, History $history)
    {
        return ($user->id == $history->user_id);
    }

    /**
     * Determine whether the user can view the DocValues.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Behaviours\Histories\Attributes\Values  $value
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
    public function create(User $user, History $history)
    {
        return ($user->id == $history->user_id);
    }

    /**
     * Determine whether the user can update the DocValues.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Behaviours\Histories\Attributes\Values  $value
     * @return mixed
     */
    public function update(User $user, Value $value)
    {
        return ($user->id == $value->user_id);
    }

    /**
     * Determine whether the user can delete the DocValues.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Behaviours\Histories\Attributes\Values  $value
     * @return mixed
     */
    public function delete(User $user, Value $value)
    {
        return ($user->id == $value->user_id);
    }

    /**
     * Determine whether the user can restore the DocValues.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Behaviours\Histories\Attributes\Values  $value
     * @return mixed
     */
    public function restore(User $user, Value $value)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the DocValues.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Behaviours\Histories\Attributes\Values  $value
     * @return mixed
     */
    public function forceDelete(User $user, Value $value)
    {
        //
    }
}
