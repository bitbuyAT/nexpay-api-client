<?php

namespace bitbuyAT\Nexpay\Facade;

use bitbuyAT\Nexpay\Contracts\Client;
use Illuminate\Support\Facades\Facade;

class Nexpay extends Facade
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
