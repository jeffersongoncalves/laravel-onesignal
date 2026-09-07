<?php

use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\LaravelOnesignal\Facades\OneSignal;

it('gets a user by the default alias label', function () {
    Http::fake(['api.onesignal.com/api/v1/apps/test-app-id/users/by/external_id/u1' => Http::response(['identity' => ['external_id' => 'u1']])]);

    OneSignal::users()->get('u1');

    Http::assertSent(fn ($request) => $request->url() === 'https://api.onesignal.com/api/v1/apps/test-app-id/users/by/external_id/u1');
});

it('gets a user by a custom alias label', function () {
    Http::fake(['api.onesignal.com/api/v1/apps/test-app-id/users/by/onesignal_id/u1' => Http::response(['identity' => []])]);

    OneSignal::users()->get('u1', 'onesignal_id');

    Http::assertSent(fn ($request) => $request->url() === 'https://api.onesignal.com/api/v1/apps/test-app-id/users/by/onesignal_id/u1');
});

it('creates a user with identity, subscription and tags', function () {
    Http::fake(['api.onesignal.com/api/v1/apps/test-app-id/users' => Http::response(['identity' => ['external_id' => 'u1']])]);

    OneSignal::users()->create(externalId: 'u1', email: 'jane@example.com', tags: ['plan' => 'pro']);

    Http::assertSent(fn ($request) => $request['identity'] === ['external_id' => 'u1']
        && $request['subscriptions'] === [['type' => 'Email', 'token' => 'jane@example.com']]
        && $request['tags'] === ['plan' => 'pro']);
});

it('deletes a user by alias', function () {
    Http::fake(['api.onesignal.com/api/v1/apps/test-app-id/users/by/external_id/u1' => Http::response(['success' => true])]);

    OneSignal::users()->delete('u1');

    Http::assertSent(fn ($request) => $request->method() === 'DELETE');
});
