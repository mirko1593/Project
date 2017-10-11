<?php

use Faker\Generator as Faker;

$factory->define(App\Corporation::class, function (Faker $faker) {
    return [
        'name' => $faker->company
    ];
});
