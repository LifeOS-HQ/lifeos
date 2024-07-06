<?php

namespace Database\Factories\Diet\Diary;

use App\Models\Diet\Diary\Day;
use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DayFactory extends Factory
{
    protected $model = Day::class;

    public function definition()
    {
        return [
            'user_id' => factory(User::class)->create()->id,
            'at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'rating_points' => $this->faker->numberBetween(1, 5),
            'rating_comment' => $this->faker->words(asText: true),
            'calories' => $this->faker->numberBetween(1000, 5000),
            'protein' => $this->faker->numberBetween(50, 200),
            'carbohydrate' => $this->faker->numberBetween(50, 200),
            'fat' => $this->faker->numberBetween(50, 200),
        ];
    }
}
