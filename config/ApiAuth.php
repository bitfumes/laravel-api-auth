<?php

return [
    'models' => [
        'user' => App\User::class,
    ],
    'resources' => [
        'user' => Bitfumes\ApiAuth\Http\Resources\UserResource::class,
    ],
    'front_url'          => 'http://localhost:3000',
    'reset_url'          => 'password/reset',
    'verify_url'         => 'email/verify',
];
