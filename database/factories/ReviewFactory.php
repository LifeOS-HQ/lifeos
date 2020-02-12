<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Reviews\Review;
use App\User;
use Faker\Generator as Faker;

$factory->define(Review::class, function (Faker $faker) {
    return [
        'user_id' => factory(User::class),
        'at' => $faker->date,
        'title' => $faker->sentence,
    ];
});
