<?php

use App\Enums\MovieGenre;
use Illuminate\Support\Arr;
use Faker\Generator as Faker;

$factory->define(App\Movie::class, function (Faker $faker) {
    $faker->addProvider(new \Xylis\FakerCinema\Provider\Movie($faker));

    $movieGenres = MovieGenre::getValues();

    return [
        'title' => $faker->movie,
        'genre' => Arr::random($movieGenres),
        'released_date' => $faker->dateTimeBetween($startDate = '-10 years', $endDate = '-1 month'),
        'created_time' => date('Y-m-d H:i:s')
    ];
});
