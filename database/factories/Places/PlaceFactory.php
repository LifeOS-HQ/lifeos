<?php

namespace Database\Factories\Places;

use Illuminate\Database\Eloquent\Factories\Factory;

class PlaceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => factory(\App\User::class)->create(),
            'title' => $this->faker->word,
        ];
    }
}
