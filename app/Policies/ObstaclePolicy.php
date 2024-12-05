<?php

namespace App\Policies;

use App\Models\Obstacles\Obstacle;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ObstaclePolicy
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
     * Determine whether the user can view the DocObstacle.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Obstacles\Obstacle  $obstacle
     * @return mixed
     */
    public function view(User $user, Obstacle $obstacle)
    {
        return ($user->id == $obstacle->user_id);
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
     * Determine whether the user can update the DocObstacle.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Obstacles\Obstacle  $obstacle
     * @return mixed
     */
    public function update(User $user, Obstacle $obstacle)
    {
        return ($user->id == $obstacle->user_id);
    }

    /**
     * Determine whether the user can delete the DocObstacle.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Obstacles\Obstacle  $obstacle
     * @return mixed
     */
    public function delete(User $user, Obstacle $obstacle)
    {
        return ($user->id == $obstacle->user_id);
    }

    /**
     * Determine whether the user can restore the DocObstacle.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Obstacles\Obstacle  $obstacle
     * @return mixed
     */
    public function restore(User $user, Obstacle $obstacle)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the DocObstacle.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Obstacles\Obstacle  $obstacle
     * @return mixed
     */
    public function forceDelete(User $user, Obstacle $obstacle)
    {
        //
    }
}
