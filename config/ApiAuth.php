<?php

return [
    'models' => [
        'user' => App\User::class,
    ],
    'resources' => [
        'user' => Bitfumes\ApiAuth\Http\Resources\UserResource::class,
    ],
    'front_url'          => env('FRONT_URL', 'http://localhost:3000'),
    'reset_url'          => env('API_AUTH_RESET_URL', 'password/reset'),
    'verify_url'         => env('API_AUTH_VERIFY_URL', 'email/verify'),
    'welcome_email'      => Bitfumes\ApiAuth\Mail\WelcomeEmail::class,
    'notifications'      => [
        'reset'  => Bitfumes\ApiAuth\Notifications\UserPasswordReset::class,
        'verify' => Bitfumes\ApiAuth\Notifications\VerifyEmail::class,
    ],
    'avatar' => [
        'disk'         => env('API_AUTH_AVATAR_DISK', 'public'),
        'path'         => env('API_AUTH_AVATAR_PATH', 'images/avatars'),
        'thumb_width'  => env('API_AUTH_AVATAR_WIDTH', 50),
        'thumb_height' => env('API_AUTH_AVATAR_HEIGHT', 50),
    ],
];
