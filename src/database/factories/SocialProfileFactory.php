<?php

use Faker\Generator as Faker;
use Bitfumes\ApiAuth\Tests\User;

$factory->define(Bitfumes\ApiAuth\SocialProfile::class, function (Faker $faker) {
    return [
        'id'               => $faker->randomNumber,
        'user_id'          => function () {
            return factory(User::class)->create()->id;
        },
        'service_id' => 'asdfasdfadsfadsf',
        'avatar'     => $faker->url,
        'service'    => 'google',
    ];
});
