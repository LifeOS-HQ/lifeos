<?php

namespace Database\Factories\Diet\Foods;

use App\Models\Diet\Foods\Food;
use Illuminate\Database\Eloquent\Factories\Factory;

class FoodFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Food::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'calories' => $this->faker->randomFloat(3, 0, 1000),
            'carbohydrate' => $this->faker->randomFloat(3, 0, 1000),
            'fat' => $this->faker->randomFloat(3, 0, 1000),
            'protein' => $this->faker->randomFloat(3, 0, 1000),
        ];
    }
}
