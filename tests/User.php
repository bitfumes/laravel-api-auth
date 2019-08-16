<?php

namespace Bitfumes\ApiAuth\Tests;

use Bitfumes\ApiAuth\Traits\ApiAuth;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Bitfumes\ApiAuth\Contract\HasApiAuth;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements HasApiAuth, JWTSubject
{
    use Notifiable;
    use ApiAuth;

    /**
     * @param $value
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
