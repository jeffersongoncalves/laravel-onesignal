<?php

namespace JeffersonGoncalves\LaravelOnesignal\Resources;

use JeffersonGoncalves\LaravelOnesignal\OneSignalClient;

class Segments
{
    public function __construct(
        protected OneSignalClient $client,
    ) {}

    public function list(int $limit = 50, int $offset = 0): array
    {
        return $this->client->get("/api/v1/apps/{$this->client->appId()}/segments", [
            'limit' => $limit,
            'offset' => $offset,
        ]);
    }

    /** @param  array<int, array<string, mixed>>|null  $filters */
    public function create(string $name, ?array $filters = null): array
    {
        return $this->client->post("/api/v1/apps/{$this->client->appId()}/segments", [
            'name' => $name,
            'filters' => $filters ?? [
                ['field' => 'session_count', 'relation' => '>', 'value' => '0'],
            ],
        ]);
    }

    public function delete(string $id): array
    {
        return $this->client->delete("/api/v1/apps/{$this->client->appId()}/segments/{$id}");
    }
}
