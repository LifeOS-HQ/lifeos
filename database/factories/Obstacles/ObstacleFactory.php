<?php

namespace Database\Factories\Obstacles;

use App\User;
use App\Models\Days\Day;
use Illuminate\Database\Eloquent\Factories\Factory;

class ObstacleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => factory(User::class)->create(),
            'created_day_id' => Day::factory()->create(),
            'overcome_day_id' => Day::factory()->create(),
            'alchemized_day_id' => Day::factory()->create(),
            'level' => $this->faker->numberBetween(1, 10),
            'title' => $this->faker->words(3, true),
            'challenge' => $this->faker->words(20, true),
            'wish' => $this->faker->words(8, true),
            'outcome' => $this->faker->words(20, true),
            'obstacle' => $this->faker->words(20, true),
            'plan' => $this->faker->words(20, true),
            'loot' => $this->faker->words(20, true),
            'is_active' => $this->faker->boolean,
        ];
    }
}
