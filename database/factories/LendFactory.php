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
        'lending_date' => $faker->dateTimeBetween($startDate = '-3 months', $endDate = '-1 day'),
        'returned_date' => Arr::random([null, new DateTime()]),
        'created_time' => date('Y-m-d H:i:s')
    ];
});
