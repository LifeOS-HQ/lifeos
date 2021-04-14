<?php

namespace Database\Factories\Comments;

use App\Models\Comments\Comment;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => factory(\App\User::class)->create(),
            'commentable_type' => \App\Models\Contacts\Contact::class,
            'commentable_id' => \App\Models\Contacts\Contact::factory()->create()->id,
            'title' => $this->faker->sentence,
            'body' => $this->faker->paragraph,
        ];
    }
}
