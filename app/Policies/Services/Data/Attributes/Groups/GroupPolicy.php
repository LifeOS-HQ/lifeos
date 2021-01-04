<?php

namespace App\Policies\Services\Data\Attributes\Groups;

use App\Models\Services\Data\Attributes\Groups\Group;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GroupPolicy
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
     * Determine whether the user can view the DocGroup.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Services\Data\Attributes\Groups\Group  $group
     * @return mixed
     */
    public function view(User $user, Group $group)
    {
        return ($user->id == $group->user_id);
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
     * Determine whether the user can update the DocGroup.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Services\Data\Attributes\Groups\Group  $group
     * @return mixed
     */
    public function update(User $user, Group $group)
    {
        return ($user->id == $group->user_id);
    }

    /**
     * Determine whether the user can delete the DocGroup.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Services\Data\Attributes\Groups\Group  $group
     * @return mixed
     */
    public function delete(User $user, Group $group)
    {
        return ($user->id == $group->user_id);
    }

    /**
     * Determine whether the user can restore the DocGroup.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Services\Data\Attributes\Groups\Group  $group
     * @return mixed
     */
    public function restore(User $user, Group $group)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the DocGroup.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Services\Data\Attributes\Groups\Group  $group
     * @return mixed
     */
    public function forceDelete(User $user, Group $group)
    {
        //
    }
}
