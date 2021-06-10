<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(App\BlogUsers::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        //'avatar' => $faker->image('./tmp', 320, 320),
        'avatar' => '',
        'password' => '$2y$10$/dxwZRKhrRExHaweO7kFruK.DSCbwRaPYEJoh9G/4WP8FaPAOmKMi', // password
    ];
});
