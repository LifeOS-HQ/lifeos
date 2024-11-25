<?php

namespace Database\Factories\Services;

use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
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
            'service_id' => factory(\App\Models\Services\Service::class)->create([
                'slug' => 'exist',
                'type' => 'password',
            ]),
            'service_user_id' => $this->faker->unique()->uuid,
            'token' => $this->faker->unique()->uuid,
            'token_secret' => $this->faker->unique()->uuid,
            'refresh_token' => $this->faker->unique()->uuid,
            'username' => $this->faker->userName,
            'password' => $this->faker->password,
            'expires_in' => $this->faker->numberBetween(1, 10),
            'expires_at' => $this->faker->dateTimeBetween('+1 hour', '+1 year'),
        ];
    }
}
