<?php

namespace App\Policies\Services\Data;

use App\Models\Services\Data\Type;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TypePolicy
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
     * Determine whether the user can view the DocType.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Services\Data\Type  $type
     * @return mixed
     */
    public function view(User $user, Type $type)
    {
        return ($user->id == $type->user_id);
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
     * Determine whether the user can update the DocType.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Services\Data\Type  $type
     * @return mixed
     */
    public function update(User $user, Type $type)
    {
        return ($user->id == $type->user_id);
    }

    /**
     * Determine whether the user can delete the DocType.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Services\Data\Type  $type
     * @return mixed
     */
    public function delete(User $user, Type $type)
    {
        return ($user->id == $type->user_id);
    }

    /**
     * Determine whether the user can restore the DocType.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Services\Data\Type  $type
     * @return mixed
     */
    public function restore(User $user, Type $type)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the DocType.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Services\Data\Type  $type
     * @return mixed
     */
    public function forceDelete(User $user, Type $type)
    {
        //
    }
}
