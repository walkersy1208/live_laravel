<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Admin\Carousel;
use Faker\Generator as Faker;

$factory->define(Carousel::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'url' => '/upload/xxxxxx',
        'is_visiable' => 'Y',
        'image_url' => 'xxxxx',
    ];
});
