<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Work\Month;
use App\Models\Work\Year;
use App\User;
use Faker\Generator as Faker;

$factory->define(Month::class, function (Faker $faker, array $attributes = []) {
    return [
        'user_id' => factory(User::class),
        'year_id' => factory(Year::class),
        'date' => now(),
    ];
});
