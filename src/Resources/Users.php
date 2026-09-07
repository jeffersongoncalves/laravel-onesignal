<?php

namespace JeffersonGoncalves\LaravelOnesignal\Resources;

use JeffersonGoncalves\LaravelOnesignal\OneSignalClient;

class Users
{
    public function __construct(
        protected OneSignalClient $client,
    ) {}

    public function get(string $aliasId, string $aliasLabel = 'external_id'): array
    {
        return $this->client->get("/api/v1/apps/{$this->client->appId()}/users/by/{$aliasLabel}/{$aliasId}");
    }

    /** @param  array<string, mixed>|null  $tags */
    public function create(?string $externalId = null, ?string $email = null, ?array $tags = null): array
    {
        $payload = [];

        if ($externalId !== null) {
            $payload['identity'] = ['external_id' => $externalId];
        }

        if ($email !== null) {
            $payload['subscriptions'] = [['type' => 'Email', 'token' => $email]];
        }

        if ($tags !== null) {
            $payload['tags'] = $tags;
        }

        return $this->client->post("/api/v1/apps/{$this->client->appId()}/users", $payload);
    }

    public function delete(string $aliasId, string $aliasLabel = 'external_id'): array
    {
        return $this->client->delete("/api/v1/apps/{$this->client->appId()}/users/by/{$aliasLabel}/{$aliasId}");
    }
}
