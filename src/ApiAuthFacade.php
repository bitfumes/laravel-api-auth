<?php

namespace Bitfumes\ApiAuth;

use Illuminate\Support\Facades\Facade;

class ApiAuthFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'api-auth';
    }
}