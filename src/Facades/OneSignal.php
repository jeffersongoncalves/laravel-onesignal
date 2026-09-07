<?php

namespace JeffersonGoncalves\LaravelOnesignal\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \JeffersonGoncalves\LaravelOnesignal\OneSignal
 */
class OneSignal extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \JeffersonGoncalves\LaravelOnesignal\OneSignal::class;
    }
}
