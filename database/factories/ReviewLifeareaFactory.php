<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Reviews\Lifearea;
use App\Models\Reviews\Review;
use App\User;
use Faker\Generator as Faker;

$factory->define(Lifearea::class, function (Faker $faker) {
    return [
        'user_id' => factory(User::class),
        'review_id' => factory(Review::class),
        'lifearea_id' => factory(App\Models\Lifeareas\Lifearea::class),
        'rating' => $faker->randomNumber,
        'comment' => $faker->sentence,
    ];
});
