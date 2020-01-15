<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Journals\Journal;
use App\User;
use Faker\Generator as Faker;

$factory->define(Journal::class, function (Faker $faker) {

    $today = today();

    return [
        'user_id' => factory(User::class)->create(),
        'date' => $faker->dateTimeBetween($today->startOfMonth(), $today->endOfMonth()),
        'body' => $faker->sentence,
    ];
});
