<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(App\Articles::class, function (Faker $faker) {
    return [
        'user_id' => '1',
        'title' => $faker->sentence,
        'content' => $faker->text,
    ];
});
