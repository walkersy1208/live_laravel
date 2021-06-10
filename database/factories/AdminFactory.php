<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\admin\admin;
use Faker\Generator as Faker;

$factory->define(admin::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        //'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'password' => bcrypt('11111'),
    ];
});
