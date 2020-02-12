<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Lifeareas\Lifearea;
use App\User;
use Faker\Generator as Faker;

$factory->define(Lifearea::class, function (Faker $faker) {
    return [
        'user_id' => factory(User::class),
        'title' => $faker->word,
    ];
});
