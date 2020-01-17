<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Journals\Gratitude\Gratitude;
use App\Models\Journals\Journal;
use App\User;
use Faker\Generator as Faker;

$factory->define(Gratitude::class, function (Faker $faker) {

    $journal = factory(Journal::class)->create();

    return [
        'user_id' => $journal->user_id,
        'journal_id' => $journal->id,
        'text' => $faker->sentence,
    ];
});
