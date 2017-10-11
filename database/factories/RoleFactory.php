<?php

use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(App\Role::class, function (Faker $faker) {
    $jobTitle = $faker->jobTitle;

    return [
        'name' => Str::snake($jobTitle),
        'label' => $jobTitle
    ];
});
