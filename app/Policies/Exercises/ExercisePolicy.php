<?php

namespace App\Policies\Exercises;

use App\Models\Exercises\Exercise;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExercisePolicy
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
     * Determine whether the user can view the DocExercise.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Workouts\Exercise  $exercise
     * @return mixed
     */
    public function view(User $user, Exercise $exercise)
    {
        return ($user->id == $exercise->user_id);
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
     * Determine whether the user can update the DocExercise.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Workouts\Exercise  $exercise
     * @return mixed
     */
    public function update(User $user, Exercise $exercise)
    {
        return ($user->id == $exercise->user_id);
    }

    /**
     * Determine whether the user can delete the DocExercise.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Workouts\Exercise  $exercise
     * @return mixed
     */
    public function delete(User $user, Exercise $exercise)
    {
        return ($user->id == $exercise->user_id);
    }

    /**
     * Determine whether the user can restore the DocExercise.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Workouts\Exercise  $exercise
     * @return mixed
     */
    public function restore(User $user, Exercise $exercise)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the DocExercise.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Workouts\Exercise  $exercise
     * @return mixed
     */
    public function forceDelete(User $user, Exercise $exercise)
    {
        //
    }
}
