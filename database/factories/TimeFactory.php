<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Work\Month;
use App\Models\Work\Time;
use App\Models\Work\Year;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

$factory->define(Time::class, function (Faker $faker, $attributes) {

    $month = Arr::has($attributes, 'month_id') ? Month::find($attributes['month_id']) : factory(Month::class)->create();
    $startAt = $faker->dateTimeBetween($month->date->startOfMonth(), $month->date->endOfMonth());
    $endAt = (new Carbon($startAt->format('y-m-d H:i:s')))->addHours(9);

    return [
        'user_id' => factory(User::class),
        'month_id' => $month->id,
        'start_at' => $startAt,
        'end_at' => $endAt,
    ];
});
