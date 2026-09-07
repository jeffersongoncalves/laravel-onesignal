<?php

namespace Jeffersongoncalves\LaravelOnesignal\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Jeffersongoncalves\LaravelOnesignal\LaravelOnesignal
 */
class LaravelOnesignal extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'laravel-onesignal';
    }
}
