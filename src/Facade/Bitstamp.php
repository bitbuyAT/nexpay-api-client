<?php

namespace bitbuyAT\Globitex\Facade;

use bitbuyAT\Globitex\Contracts\Client;
use Illuminate\Support\Facades\Facade;

class Globitex extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    protected static function getFacadeAccessor()
    {
        return Client::class;
    }
}
