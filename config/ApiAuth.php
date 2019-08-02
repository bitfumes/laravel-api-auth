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
    'welcome_email'      => Bitfumes\ApiAuth\Mail\WelcomeEmail::class,
    'notifications'      => [
        'reset'  => Bitfumes\ApiAuth\Notifications\UserPasswordReset::class,
        'verify' => Bitfumes\ApiAuth\Notifications\VerifyEmail::class,
    ],
    'avatar' => [
        'disk'         => 'public',
        'path'         => 'avatar',
        'thumb_width'  => 50,
        'thumb_height' => 50,
    ],
];
