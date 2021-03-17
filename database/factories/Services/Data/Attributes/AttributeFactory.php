<?php

namespace Database\Factories\Services\Data\Attributes;

use App\Models\Services\Data\Attributes\Attribute;
use App\Models\Services\Data\Attributes\Groups\Group;
use App\Models\Services\Data\Type;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AttributeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Attribute::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->word;

        return [
            'group_id' => Group::factory(),
            'type_id' => Type::factory(),
            'name' => $name,
            'slug' => Str::slug($name),
            'priority' => 1,
        ];
    }
}
