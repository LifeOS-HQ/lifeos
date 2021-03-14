<?php

namespace App\Policies\Workouts;

use App\Models\Workouts\Workout;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class WorkoutPolicy
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
     * Determine whether the user can view the DocWorkout.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Workouts\Workout  $workout
     * @return mixed
     */
    public function view(User $user, Workout $workout)
    {
        return ($user->id == $workout->user_id);
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
     * Determine whether the user can update the DocWorkout.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Workouts\Workout  $workout
     * @return mixed
     */
    public function update(User $user, Workout $workout)
    {
        return ($user->id == $workout->user_id);
    }

    /**
     * Determine whether the user can delete the DocWorkout.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Workouts\Workout  $workout
     * @return mixed
     */
    public function delete(User $user, Workout $workout)
    {
        return ($user->id == $workout->user_id);
    }

    /**
     * Determine whether the user can restore the DocWorkout.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Workouts\Workout  $workout
     * @return mixed
     */
    public function restore(User $user, Workout $workout)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the DocWorkout.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Workouts\Workout  $workout
     * @return mixed
     */
    public function forceDelete(User $user, Workout $workout)
    {
        //
    }
}
