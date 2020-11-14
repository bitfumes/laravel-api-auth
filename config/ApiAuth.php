<?php

return [
    /**
     * Custom Models
     */
    'models' => [
        'user' => App\User::class,
    ],
    /**
     * Override existing resources
     */
    'resources' => [
        'user' => Bitfumes\ApiAuth\Http\Resources\UserResource::class,
    ],
    /**
     * Url of your frontend, reset password url and verify email url
     */
    'front_url'          => env('FRONT_URL', 'http://localhost:3000'),
    'reset_url'          => env('API_AUTH_RESET_URL', 'password/reset'),
    'verify_url'         => env('API_AUTH_VERIFY_URL', 'email/verify'),
    'welcome_email'      => Bitfumes\ApiAuth\Mail\WelcomeEmail::class,
    'notifications'      => [
        'reset'  => Bitfumes\ApiAuth\Notifications\UserPasswordReset::class,
        'verify' => Bitfumes\ApiAuth\Notifications\VerifyEmail::class,
    ],
    /**
     * Avatar Settings
     * Define disk and path to store avatar images
     * Define width and height of thumb image created for user
     */
    'avatar' => [
        'disk'         => env('API_AUTH_AVATAR_DISK', 'public'),
        'path'         => env('API_AUTH_AVATAR_PATH', 'images/avatars'),
        'thumb_width'  => env('API_AUTH_AVATAR_WIDTH', 50),
        'thumb_height' => env('API_AUTH_AVATAR_HEIGHT', 50),
    ],
    /**
     * Custom Validation Rules
     * your cusom validation rules for register and update
     * this will merge with existing rules
     */
    'validations' => function () {
        return ['fumesid' =>  'max:10|unique:users,fumesid,' . auth()->id()];
    },
];
