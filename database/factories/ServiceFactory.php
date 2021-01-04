<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Services\Service;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Service::class, function (Faker $faker) {

    $name = $faker->word;

    return [
        'name' => $name,
        'slug' => Str::slug($name, '-', 'de'),
    ];
});
