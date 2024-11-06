<?php

namespace Database\Factories\Behaviours\Histories\Attributes;

use App\User;
use App\Models\Behaviours\History;
use App\Models\Services\Data\Attributes\Attribute;
use Illuminate\Database\Eloquent\Factories\Factory;

class ValueFactory extends Factory
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
            'history_id' => History::factory(),
            'attribute_id' => Attribute::factory(),
            'raw' => (string) $this->faker->randomFloat(2, 1, 100),
        ];
    }
}
