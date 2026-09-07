<?php

namespace JeffersonGoncalves\LaravelOnesignal\Tests;

use JeffersonGoncalves\LaravelOnesignal\LaravelOnesignalServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            LaravelOnesignalServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('laravel-onesignal.app_id', 'test-app-id');
        $app['config']->set('laravel-onesignal.rest_api_key', 'test-rest-api-key');
    }
}
