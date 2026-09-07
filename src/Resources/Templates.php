<?php

namespace JeffersonGoncalves\LaravelOnesignal\Resources;

use JeffersonGoncalves\LaravelOnesignal\OneSignalClient;

class Templates
{
    public function __construct(
        protected OneSignalClient $client,
    ) {}

    public function list(int $limit = 50, int $offset = 0): array
    {
        return $this->client->get('/api/v1/templates', [
            'app_id' => $this->client->appId(),
            'limit' => $limit,
            'offset' => $offset,
        ]);
    }

    public function get(string $id): array
    {
        return $this->client->get("/api/v1/templates/{$id}", [
            'app_id' => $this->client->appId(),
        ]);
    }

    public function create(string $name, ?string $message = null, ?string $heading = null): array
    {
        $payload = [
            'app_id' => $this->client->appId(),
            'name' => $name,
        ];

        if ($message !== null) {
            $payload['contents'] = ['en' => $message];
        }

        if ($heading !== null) {
            $payload['headings'] = ['en' => $heading];
        }

        return $this->client->post('/api/v1/templates', $payload);
    }
}
