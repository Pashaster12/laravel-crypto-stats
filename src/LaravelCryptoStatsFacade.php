<?php

namespace LaravelCryptoStats;

use Illuminate\Support\Facades\Facade;

class LaravelCryptoStatsFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-crypto-stats';
    }
}
