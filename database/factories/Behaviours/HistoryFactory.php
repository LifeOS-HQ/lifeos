<?php

namespace Database\Factories\Behaviours;

use App\User;
use App\Models\Days\Day;
use App\Models\Behaviours\Behaviour;
use Illuminate\Database\Eloquent\Factories\Factory;

class HistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => factory(User::class)->create()->id,
            'behaviour_id' => Behaviour::factory()->create()->id,
            'day_id' => Day::factory(),
            'start_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'end_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
