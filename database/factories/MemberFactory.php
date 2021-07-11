<?php

use App\Enums\MemberState;
use Illuminate\Support\Arr;
use Faker\Generator as Faker;

$factory->define(App\Member::class, function (Faker $faker) {
    $memberStates = MemberState::getValues();

    return [
        'name' => $faker->name,
        'age' => $faker->numberBetween(15, 40),
        'address' => $faker->address,
        'telephone' => $faker->phoneNumber,
        'identity_number' => $faker->numerify('##-######'),
        'date_of_joined' => $faker->dateTimeBetween($startDate = '-6 months', $endDate = '-1 day'),
        'is_active' => Arr::random($memberStates),
        'created_time' => date('Y-m-d H:i:s')
    ];
});
