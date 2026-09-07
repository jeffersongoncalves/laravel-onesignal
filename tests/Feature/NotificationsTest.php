<?php

use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\LaravelOnesignal\Facades\OneSignal;

it('sends a notification to the default segment when no target is given', function () {
    Http::fake(['api.onesignal.com/api/v1/notifications' => Http::response(['id' => 'n1'])]);

    OneSignal::notifications()->send('Hello world', heading: 'Hi', url: 'https://example.com', data: ['foo' => 'bar']);

    Http::assertSent(function ($request) {
        return $request->url() === 'https://api.onesignal.com/api/v1/notifications'
            && $request['app_id'] === 'test-app-id'
            && $request['contents'] === ['en' => 'Hello world']
            && $request['headings'] === ['en' => 'Hi']
            && $request['url'] === 'https://example.com'
            && $request['data'] === ['foo' => 'bar']
            && $request['included_segments'] === ['Subscribed Users'];
    });
});

it('prioritizes segments over every other target', function () {
    Http::fake(['api.onesignal.com/*' => Http::response(['id' => 'n1'])]);

    OneSignal::notifications()->send(
        'Hello',
        segments: ['Active Users'],
        emails: ['jane@example.com'],
        playerIds: ['p1'],
        aliases: ['external_id' => ['a1']],
    );

    Http::assertSent(fn ($request) => $request['included_segments'] === ['Active Users']
        && ! isset($request['include_email_tokens'])
        && ! isset($request['include_player_ids'])
        && ! isset($request['include_aliases']));
});

it('prioritizes emails over player ids and aliases', function () {
    Http::fake(['api.onesignal.com/*' => Http::response(['id' => 'n1'])]);

    OneSignal::notifications()->send('Hello', emails: ['jane@example.com'], playerIds: ['p1'], aliases: ['external_id' => ['a1']]);

    Http::assertSent(fn ($request) => $request['include_email_tokens'] === ['jane@example.com']
        && ! isset($request['include_player_ids'])
        && ! isset($request['include_aliases']));
});

it('prioritizes player ids over aliases', function () {
    Http::fake(['api.onesignal.com/*' => Http::response(['id' => 'n1'])]);

    OneSignal::notifications()->send('Hello', playerIds: ['p1'], aliases: ['external_id' => ['a1']]);

    Http::assertSent(fn ($request) => $request['include_player_ids'] === ['p1']
        && ! isset($request['include_aliases']));
});

it('targets aliases with target channel when nothing else is set', function () {
    Http::fake(['api.onesignal.com/*' => Http::response(['id' => 'n1'])]);

    OneSignal::notifications()->send('Hello', aliases: ['external_id' => ['a1']], channel: 'email');

    Http::assertSent(fn ($request) => $request['include_aliases'] === ['external_id' => ['a1']]
        && $request['target_channel'] === 'email');
});

it('includes send_after and ttl when given', function () {
    Http::fake(['api.onesignal.com/*' => Http::response(['id' => 'n1'])]);

    OneSignal::notifications()->send('Hello', sendAfter: '2026-01-01 12:00:00 GMT-0000', ttl: 300);

    Http::assertSent(fn ($request) => $request['send_after'] === '2026-01-01 12:00:00 GMT-0000' && $request['ttl'] === 300);
});

it('lists notifications', function () {
    Http::fake(['api.onesignal.com/api/v1/notifications*' => Http::response(['notifications' => []])]);

    OneSignal::notifications()->list(10, 5);

    Http::assertSent(fn ($request) => $request->url() === 'https://api.onesignal.com/api/v1/notifications?app_id=test-app-id&limit=10&offset=5');
});

it('gets a single notification', function () {
    Http::fake(['api.onesignal.com/api/v1/notifications/n1*' => Http::response(['id' => 'n1'])]);

    $result = OneSignal::notifications()->get('n1');

    expect($result['id'])->toBe('n1');
    Http::assertSent(fn ($request) => $request->url() === 'https://api.onesignal.com/api/v1/notifications/n1?app_id=test-app-id');
});

it('cancels a notification', function () {
    Http::fake(['api.onesignal.com/api/v1/notifications/n1*' => Http::response(['success' => true])]);

    OneSignal::notifications()->cancel('n1');

    Http::assertSent(fn ($request) => $request->method() === 'DELETE'
        && $request->url() === 'https://api.onesignal.com/api/v1/notifications/n1?app_id=test-app-id');
});
