<?php

namespace JeffersonGoncalves\LaravelOnesignal\Resources;

use JeffersonGoncalves\LaravelOnesignal\OneSignalClient;

class Notifications
{
    public function __construct(
        protected OneSignalClient $client,
    ) {}

    /**
     * @param  array<int, string>|null  $segments  Included segment names.
     * @param  array<int, string>|null  $emails  Email tokens to target.
     * @param  array<int, string>|null  $playerIds  Player (subscription) IDs to target.
     * @param  array<string, array<int, string>>|null  $aliases  Alias label => alias IDs (e.g. ['external_id' => ['abc']]).
     * @param  array<string, mixed>|null  $data  Custom data payload.
     *
     * Targeting precedence: segments > emails > playerIds > aliases > default "Subscribed Users" segment.
     */
    public function send(
        string $message,
        ?string $heading = null,
        ?string $url = null,
        ?array $data = null,
        ?array $segments = null,
        ?array $emails = null,
        ?array $playerIds = null,
        ?array $aliases = null,
        ?string $channel = 'push',
        ?string $sendAfter = null,
        ?int $ttl = null,
    ): array {
        $payload = [
            'app_id' => $this->client->appId(),
            'contents' => ['en' => $message],
        ];

        if ($heading !== null) {
            $payload['headings'] = ['en' => $heading];
        }

        if ($url !== null) {
            $payload['url'] = $url;
        }

        if ($data !== null) {
            $payload['data'] = $data;
        }

        if ($segments !== null) {
            $payload['included_segments'] = $segments;
        } elseif ($emails !== null) {
            $payload['include_email_tokens'] = $emails;
        } elseif ($playerIds !== null) {
            $payload['include_player_ids'] = $playerIds;
        } elseif ($aliases !== null) {
            $payload['include_aliases'] = $aliases;
            $payload['target_channel'] = $channel ?? 'push';
        } else {
            $payload['included_segments'] = ['Subscribed Users'];
        }

        if ($sendAfter !== null) {
            $payload['send_after'] = $sendAfter;
        }

        if ($ttl !== null) {
            $payload['ttl'] = $ttl;
        }

        return $this->client->post('/api/v1/notifications', $payload);
    }

    public function list(int $limit = 50, int $offset = 0): array
    {
        return $this->client->get('/api/v1/notifications', [
            'app_id' => $this->client->appId(),
            'limit' => $limit,
            'offset' => $offset,
        ]);
    }

    public function get(string $id): array
    {
        return $this->client->get("/api/v1/notifications/{$id}", [
            'app_id' => $this->client->appId(),
        ]);
    }

    public function cancel(string $id): array
    {
        return $this->client->delete("/api/v1/notifications/{$id}", [
            'app_id' => $this->client->appId(),
        ]);
    }
}
