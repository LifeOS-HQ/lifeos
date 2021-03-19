<?php

namespace Database\Factories\Workouts\Exercises;

use App\Models\Workouts\Exercises\Exercise;
use App\Models\Workouts\Workout;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExerciseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Exercise::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'exercise_id' => \App\Models\Exercises\Exercise::factory(),
            'workout_id' => Workout::factory(),
            'goal_type' => 'reps',
        ];
    }
}
