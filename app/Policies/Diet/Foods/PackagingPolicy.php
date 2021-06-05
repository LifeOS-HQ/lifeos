<?php

namespace App\Policies\Diet\Foods;

use App\Models\Diet\Foods\Packaging;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PackagingPolicy
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
     * Determine whether the user can view the DocPackaging.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Diet\Foods\Packaging  $packaging
     * @return mixed
     */
    public function view(User $user, Packaging $packaging)
    {
        return ($user->id == $packaging->user_id);
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
     * Determine whether the user can update the DocPackaging.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Diet\Foods\Packaging  $packaging
     * @return mixed
     */
    public function update(User $user, Packaging $packaging)
    {
        return ($user->id == $packaging->user_id);
    }

    /**
     * Determine whether the user can delete the DocPackaging.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Diet\Foods\Packaging  $packaging
     * @return mixed
     */
    public function delete(User $user, Packaging $packaging)
    {
        return ($user->id == $packaging->user_id);
    }

    /**
     * Determine whether the user can restore the DocPackaging.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Diet\Foods\Packaging  $packaging
     * @return mixed
     */
    public function restore(User $user, Packaging $packaging)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the DocPackaging.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Diet\Foods\Packaging  $packaging
     * @return mixed
     */
    public function forceDelete(User $user, Packaging $packaging)
    {
        //
    }
}
