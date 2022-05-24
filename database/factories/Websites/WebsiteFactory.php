<?php

namespace Database\Factories\Websites;

use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class WebsiteFactory extends Factory
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
            'name' => 'Website ' . $this->faker->domainName,
            'directory_path' => $this->faker->word,
            'github_url' => 'https://github.com/' . $this->faker->userName . '/' . $this->faker->word,
        ];
    }
}
