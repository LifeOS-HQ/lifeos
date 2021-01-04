<?php

namespace App\Policies\Services\Data\Attributes;

use App\Models\Services\Data\Attributes\Attribute;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AttributePolicy
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
     * Determine whether the user can view the DocAttribute.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Services\Data\Attributes\Attribute  $attribute
     * @return mixed
     */
    public function view(User $user, Attribute $attribute)
    {
        return ($user->id == $attribute->user_id);
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
     * Determine whether the user can update the DocAttribute.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Services\Data\Attributes\Attribute  $attribute
     * @return mixed
     */
    public function update(User $user, Attribute $attribute)
    {
        return ($user->id == $attribute->user_id);
    }

    /**
     * Determine whether the user can delete the DocAttribute.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Services\Data\Attributes\Attribute  $attribute
     * @return mixed
     */
    public function delete(User $user, Attribute $attribute)
    {
        return ($user->id == $attribute->user_id);
    }

    /**
     * Determine whether the user can restore the DocAttribute.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Services\Data\Attributes\Attribute  $attribute
     * @return mixed
     */
    public function restore(User $user, Attribute $attribute)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the DocAttribute.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Services\Data\Attributes\Attribute  $attribute
     * @return mixed
     */
    public function forceDelete(User $user, Attribute $attribute)
    {
        //
    }
}
