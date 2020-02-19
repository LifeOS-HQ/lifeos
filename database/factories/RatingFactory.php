<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Journals\Journal;
use App\Models\Journals\Rating;
use App\User;
use Faker\Generator as Faker;

$factory->define(Rating::class, function (Faker $faker) {
    return [
        'user_id' => factory(User::class),
        'journal_id' => factory(Journal::class),
        'title' => $faker->word,
        'rating' => $faker->randomNumber,
        'comment' => $faker->sentence,
    ];
});
