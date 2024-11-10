<?php

namespace App\Policies\Behaviours\Attributes;

use App\Models\Behaviours\Attributes\Attribute;
use App\Models\Behaviours\Behaviour;
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
    public function viewAny(User $user, Behaviour $behaviour)
    {
        return ($user->id == $behaviour->user_id);
    }

    /**
     * Determine whether the user can view the DocAttribute.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Behaviours\Attributes\Attribute  $attribute
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
    public function create(User $user, Behaviour $behaviour)
    {
        return ($user->id == $behaviour->user_id);
    }

    /**
     * Determine whether the user can update the DocAttribute.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Behaviours\Attributes\Attribute  $attribute
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
     * @param  \App\Models\Behaviours\Attributes\Attribute  $attribute
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
     * @param  \App\Models\Behaviours\Attributes\Attribute  $attribute
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
     * @param  \App\Models\Behaviours\Attributes\Attribute  $attribute
     * @return mixed
     */
    public function forceDelete(User $user, Attribute $attribute)
    {
        //
    }
}
