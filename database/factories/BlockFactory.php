<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Reviews\Block;
use App\Models\Reviews\Review;
use App\User;
use Faker\Generator as Faker;

$factory->define(Block::class, function (Faker $faker) {
    return [
        'user_id' => factory(User::class),
        'review_id' => factory(Review::class),
        'title' => $faker->word,
        'body' => $faker->paragraph,
    ];
});
