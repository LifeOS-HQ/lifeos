<?php

namespace Database\Factories\Days;

use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DayFactory extends Factory
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
            'date' => $this->faker->date(),
        ];
    }
}
