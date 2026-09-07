<?php

namespace Jeffersongoncalves\LaravelOnesignal\Tests;

use Jeffersongoncalves\LaravelOnesignal\LaravelOnesignalServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            LaravelOnesignalServiceProvider::class,
        ];
    }
}
