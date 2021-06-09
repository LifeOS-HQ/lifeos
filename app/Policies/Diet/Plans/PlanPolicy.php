<?php

namespace App\Policies\Diet\Plans;

use App\Models\Diet\Plans\Plan;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PlanPolicy
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
     * Determine whether the user can view the DocPlan.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Diet\Plans\Plan  $plan
     * @return mixed
     */
    public function view(User $user, Plan $plan)
    {
        return ($user->id == $plan->user_id);
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
     * Determine whether the user can update the DocPlan.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Diet\Plans\Plan  $plan
     * @return mixed
     */
    public function update(User $user, Plan $plan)
    {
        return ($user->id == $plan->user_id);
    }

    /**
     * Determine whether the user can delete the DocPlan.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Diet\Plans\Plan  $plan
     * @return mixed
     */
    public function delete(User $user, Plan $plan)
    {
        return ($user->id == $plan->user_id);
    }

    /**
     * Determine whether the user can restore the DocPlan.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Diet\Plans\Plan  $plan
     * @return mixed
     */
    public function restore(User $user, Plan $plan)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the DocPlan.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Diet\Plans\Plan  $plan
     * @return mixed
     */
    public function forceDelete(User $user, Plan $plan)
    {
        //
    }
}
