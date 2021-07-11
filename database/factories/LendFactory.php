<?php

use Illuminate\Support\Arr;
use Faker\Generator as Faker;

$factory->define(App\Lend::class, function (Faker $faker) {
    $lend = [
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

    $lend['lateness_charge'] = function($lend) {

        if ($lend['returned_date'] <> null) {
            $interval = date_diff($lend['lending_date'], $lend['returned_date']);
            $days_diff = (int)$interval->format('%a');

            if ($days_diff > 30) {
                $days_overdue = $days_diff - 30;
                $charges_per_day = 50; // 50 cents

                return $days_overdue * $charges_per_day;
            } else {
                return 0;
            }
        }

        return null;
    };

    return $lend;
});
