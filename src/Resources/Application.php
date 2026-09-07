<?php

namespace JeffersonGoncalves\LaravelOnesignal\Resources;

use JeffersonGoncalves\LaravelOnesignal\OneSignalClient;

class Application
{
    public function __construct(
        protected OneSignalClient $client,
    ) {}

    public function get(): array
    {
        return $this->client->get("/api/v1/apps/{$this->client->appId()}");
    }
}
