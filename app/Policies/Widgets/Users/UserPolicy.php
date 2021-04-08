<?php

namespace App\Policies\Widgets\Users;

use App\Models\Widgets\Users\User as WidgetUser;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
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
     * Determine whether the user can view the DocUser.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Widgets\Users\User  $model
     * @return mixed
     */
    public function view(User $user, WidgetUser $model)
    {
        return ($user->id == $model->user_id);
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
     * Determine whether the user can update the DocUser.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Widgets\Users\User  $model
     * @return mixed
     */
    public function update(User $user, WidgetUser $model)
    {
        return ($user->id == $model->user_id);
    }

    /**
     * Determine whether the user can delete the DocUser.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Widgets\Users\User  $model
     * @return mixed
     */
    public function delete(User $user, WidgetUser $model)
    {
        return ($user->id == $model->user_id);
    }

    /**
     * Determine whether the user can restore the DocUser.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Widgets\Users\User  $model
     * @return mixed
     */
    public function restore(User $user, WidgetUser $model)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the DocUser.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Widgets\Users\User  $model
     * @return mixed
     */
    public function forceDelete(User $user, WidgetUser $model)
    {
        //
    }
}
