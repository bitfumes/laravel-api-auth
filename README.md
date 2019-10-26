# api-auth

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![GitHub issues](https://img.shields.io/github/issues/bitfumes/laravel-api-auth)](https://github.com/bitfumes/laravel-api-auth/issues)
[![Total Downloads](https://img.shields.io/packagist/dt/bitfumes/api-auth.svg?style=flat-square)](https://packagist.org/packages/bitfumes/api-auth)
[![Build Status](https://travis-ci.org/bitfumes/laravel-api-auth.svg?branch=master)](https://travis-ci.org/bitfumes/laravel-api-auth)

# Install

`composer require bitfumes/api-auth`

# Steps to follow

## Steps 1

1. Add Contract `hasApiAuth` and `JWTSubject` to your authenticatable model like shown below:
2. Add `ApiAuth` trait to your user model.

```php

use Bitfumes\ApiAuth\Traits\ApiAuth;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Bitfumes\ApiAuth\Contract\HasApiAuth;

class User extends Authenticatable implements HasApiAuth, JWTSubject
{
    use Notifiable, ApiAuth;
    ...
}
```

## Step 2

Configure your config/auth.php file to update guard details

- Update default guard to api

```php
'defaults' => [
        'guard'     => 'api',
        ...
    ],
```

- Update api guard to 'jwt'

```php
'guards' => [
        ... ,

        'api' => [
            'driver'   => 'jwt',
            ...
        ],
    ],
```

## Step 3

Add new accessor to your authenticatable model

```php
public function setPasswordAttribute($value)
{
    $this->attributes['password'] = bcrypt($value);
}
```

## Step 4

Now publish two new migrations

1.  To add avatar field to your use model.
2.  To add social login profile.

```bash
php artisan vendor:publish --tag=api-auth:migrations
```

## Step 5

After getting migrations in your laravel application, its time to have these tables in your database.

```bash
php artisan migrate
```

## Step 6

Because every user need to verify its email and to send email we are going to use laravel queue.

Now add queue driver on your `.env` file

That's it, now enjoy api auth with JWT

```
QUEUE_DRIVER=database
```

## Testing

Run the tests with:

```bash
vendor/bin/phpunit
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security-related issues, please email sarthak@bitfumes.com instead of using the issue tracker.

## License

The MIT License (MIT). Please see [License File](/LICENSE.md) for more information.
