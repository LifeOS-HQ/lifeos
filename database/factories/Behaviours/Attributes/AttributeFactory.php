<?php

namespace Database\Factories\Behaviours\Attributes;

use App\User;
use App\Models\Behaviours\Behaviour;
use App\Models\Services\Data\Attributes\Attribute;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttributeFactory extends Factory
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
            'behaviour_id' => Behaviour::factory(),
            'attribute_id' => Attribute::factory(),
            'service_slug' => 'manual',
            'default_value' => (string) $this->faker->randomFloat(2, 1, 100),
            'goal_value' => (string) $this->faker->randomFloat(2, 1, 100),
        ];
    }
}
