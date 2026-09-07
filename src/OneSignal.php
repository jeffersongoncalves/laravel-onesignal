<?php

namespace JeffersonGoncalves\LaravelOnesignal;

use JeffersonGoncalves\LaravelOnesignal\Resources\Application;
use JeffersonGoncalves\LaravelOnesignal\Resources\Notifications;
use JeffersonGoncalves\LaravelOnesignal\Resources\Segments;
use JeffersonGoncalves\LaravelOnesignal\Resources\Templates;
use JeffersonGoncalves\LaravelOnesignal\Resources\Users;

/**
 * Entry point exposing one resource per OneSignal REST API group.
 */
class OneSignal
{
    protected OneSignalClient $client;

    public function __construct(string $appId, string $restApiKey)
    {
        $this->client = new OneSignalClient($appId, $restApiKey);
    }

    public function notifications(): Notifications
    {
        return new Notifications($this->client);
    }

    public function segments(): Segments
    {
        return new Segments($this->client);
    }

    public function users(): Users
    {
        return new Users($this->client);
    }

    public function templates(): Templates
    {
        return new Templates($this->client);
    }

    public function app(): Application
    {
        return new Application($this->client);
    }
}
