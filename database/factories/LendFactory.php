<?php

use Illuminate\Support\Arr;
use Faker\Generator as Faker;

$factory->define(App\Lend::class, function (Faker $faker) {
    return [
        'movie_id' => function() {
            return factory(App\Movie::class)->create()->id;
        },
        'member_id' => function() {
            return factory(App\Member::class)->create()->id;
        },
        'lending_date' => $faker->dateTime()->format('Y-m-d H:i:s'),
        'returned_date' => Arr::random([null, date('Y-m-d H:i:s')]),
        'lateness_charge' => 50, // 50 cents per day
        'created_time' => date('Y-m-d H:i:s')
    ];
});
