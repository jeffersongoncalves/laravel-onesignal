<?php

use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\LaravelOnesignal\Facades\OneSignal;

it('lists segments', function () {
    Http::fake(['api.onesignal.com/api/v1/apps/test-app-id/segments*' => Http::response(['segments' => []])]);

    OneSignal::segments()->list(10, 0);

    Http::assertSent(fn ($request) => $request->url() === 'https://api.onesignal.com/api/v1/apps/test-app-id/segments?limit=10&offset=0');
});

it('creates a segment with the default filter', function () {
    Http::fake(['api.onesignal.com/api/v1/apps/test-app-id/segments' => Http::response(['id' => 's1'])]);

    OneSignal::segments()->create('Active Users');

    Http::assertSent(fn ($request) => $request['name'] === 'Active Users'
        && $request['filters'] === [['field' => 'session_count', 'relation' => '>', 'value' => '0']]);
});

it('creates a segment with custom filters', function () {
    Http::fake(['api.onesignal.com/api/v1/apps/test-app-id/segments' => Http::response(['id' => 's1'])]);

    $filters = [['field' => 'tag', 'key' => 'vip', 'relation' => '=', 'value' => 'true']];

    OneSignal::segments()->create('VIPs', $filters);

    Http::assertSent(fn ($request) => $request['filters'] === $filters);
});

it('deletes a segment', function () {
    Http::fake(['api.onesignal.com/api/v1/apps/test-app-id/segments/s1' => Http::response(['success' => true])]);

    OneSignal::segments()->delete('s1');

    Http::assertSent(fn ($request) => $request->method() === 'DELETE');
});
