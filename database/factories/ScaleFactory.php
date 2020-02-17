<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Lifeareas\Lifearea;
use App\Models\Lifeareas\Scale;
use App\User;
use Faker\Generator as Faker;

$factory->define(Scale::class, function (Faker $faker) {
    return [
        'user_id' => factory(User::class),
        'lifearea_id' => factory(Lifearea::class),
        'value' => $faker->randomNumber,
        'description' => $faker->sentence,
    ];
});
