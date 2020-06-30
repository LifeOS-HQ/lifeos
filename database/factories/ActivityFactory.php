<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Activities\Activity;
use App\Models\Lifeareas\Lifearea;
use App\User;
use Faker\Generator as Faker;

$factory->define(Activity::class, function (Faker $faker) {
    return [
        'user_id' => factory(User::class),
        'lifearea_id' => factory(Lifearea::class),
        'title' => $faker->word,
    ];
});
