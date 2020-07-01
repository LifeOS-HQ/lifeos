<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Activities\Activity;
use App\Models\Journals\Journal;
use App\User;
use Faker\Generator as Faker;

$factory->define(\App\Models\Journals\Activities\Activity::class, function (Faker $faker) {
    return [
        'user_id' => factory(User::class),
        'activity_id' => factory(Activity::class),
        'journal_id' => factory(Journal::class),
        'rating' => $faker->randomNumber,
        'comment' => $faker->sentence,
    ];
});
