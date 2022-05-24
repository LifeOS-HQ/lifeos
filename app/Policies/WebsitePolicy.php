<?php

namespace App\Policies;

use App\Models\Websites\Website;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class WebsitePolicy
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
     * Determine whether the user can view the DocWebsite.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Websites\Website  $website
     * @return mixed
     */
    public function view(User $user, Website $website)
    {
        return ($user->id == $website->user_id);
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
     * Determine whether the user can update the DocWebsite.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Websites\Website  $website
     * @return mixed
     */
    public function update(User $user, Website $website)
    {
        return ($user->id == $website->user_id);
    }

    /**
     * Determine whether the user can delete the DocWebsite.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Websites\Website  $website
     * @return mixed
     */
    public function delete(User $user, Website $website)
    {
        return ($user->id == $website->user_id);
    }

    /**
     * Determine whether the user can restore the DocWebsite.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Websites\Website  $website
     * @return mixed
     */
    public function restore(User $user, Website $website)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the DocWebsite.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Websites\Website  $website
     * @return mixed
     */
    public function forceDelete(User $user, Website $website)
    {
        //
    }
}
