<?php

namespace JeffersonGoncalves\LaravelOnesignal;

use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\LaravelOnesignal\Exceptions\OneSignalException;

/**
 * Thin wrapper around Laravel's Http client for the OneSignal REST API
 * (https://api.onesignal.com), authenticated with the app's REST API key
 * as a literal "Authorization: Basic {key}" header (not HTTP Basic Auth
 * base64 encoding).
 */
class OneSignalClient
{
    protected const BASE_URL = 'https://api.onesignal.com';

    public function __construct(
        protected string $appId,
        protected string $restApiKey,
    ) {}

    public function appId(): string
    {
        return $this->appId;
    }

    /** @param array<string, mixed> $query */
    public function get(string $path, array $query = []): array
    {
        return $this->request('get', $path, array_filter($query, fn ($value) => $value !== null));
    }

    /** @param array<string, mixed>|null $body */
    public function post(string $path, ?array $body = null): array
    {
        return $this->request('post', $path, $body);
    }

    /** @param array<string, mixed> $query */
    public function delete(string $path, array $query = []): array
    {
        $query = array_filter($query, fn ($value) => $value !== null);

        // Laravel's Http client sends DELETE data as a JSON body, but OneSignal
        // expects app_id on the query string, so it is appended to the path.
        return $this->request('delete', $query === [] ? $path : $path.'?'.http_build_query($query));
    }

    /** @param array<string, mixed>|null $data */
    protected function request(string $method, string $path, ?array $data = null): array
    {
        $response = Http::withHeaders([
            'Authorization' => "Basic {$this->restApiKey}",
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])
            ->baseUrl(self::BASE_URL)
            ->{$method}($path, $data ?? []);

        if ($response->failed()) {
            throw OneSignalException::fromResponse($response);
        }

        return (array) ($response->json() ?? []);
    }
}
