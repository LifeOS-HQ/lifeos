<?php

namespace App\Policies\Lifeareas\Levels\Goals;

use App\Models\Lifeareas\Levels\Goals\Goal;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GoalPolicy
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
     * Determine whether the user can view the DocGoal.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Lifeareas\Levels\Goals\Goal  $goal
     * @return mixed
     */
    public function view(User $user, Goal $goal)
    {
        return ($user->id == $goal->user_id);
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
     * Determine whether the user can update the DocGoal.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Lifeareas\Levels\Goals\Goal  $goal
     * @return mixed
     */
    public function update(User $user, Goal $goal)
    {
        return ($user->id == $goal->user_id);
    }

    /**
     * Determine whether the user can delete the DocGoal.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Lifeareas\Levels\Goals\Goal  $goal
     * @return mixed
     */
    public function delete(User $user, Goal $goal)
    {
        return ($user->id == $goal->user_id);
    }

    /**
     * Determine whether the user can restore the DocGoal.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Lifeareas\Levels\Goals\Goal  $goal
     * @return mixed
     */
    public function restore(User $user, Goal $goal)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the DocGoal.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Lifeareas\Levels\Goals\Goal  $goal
     * @return mixed
     */
    public function forceDelete(User $user, Goal $goal)
    {
        //
    }
}
