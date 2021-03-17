<?php

namespace Database\Factories\Services\Data\Attributes\Groups;

use App\Models\Services\Data\Attributes\Groups\Group;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class GroupFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Group::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->word;

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'priority' => 1,
        ];
    }
}
