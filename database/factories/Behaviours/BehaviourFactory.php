<?php

namespace Database\Factories\Behaviours;

use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BehaviourFactory extends Factory
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
            'habitica_uuid' => $this->faker->uuid,
            'name' => $this->faker->name,
        ];
    }
}
