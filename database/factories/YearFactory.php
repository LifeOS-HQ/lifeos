<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Work\Year;
use App\User;
use Faker\Generator as Faker;

$factory->define(Year::class, function (Faker $faker) {
    return [
        'user_id' => factory(User::class),
        'date' => now(),
    ];
});
