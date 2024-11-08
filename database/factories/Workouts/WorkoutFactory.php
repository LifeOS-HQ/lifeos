<?php

namespace Database\Factories\Workouts;

use App\Models\Workouts\Workout;
use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkoutFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Workout::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => factory(User::class),
            'name' => $this->faker->word,
        ];
    }
}
