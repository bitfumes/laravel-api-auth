<?php

namespace Bitfumes\ApiAuth\Contract;

interface HasApiAuth
{
    public function sendEmailVerificationNotification();

    public function getJWTIdentifier();

    public function getJWTCustomClaims();
}
