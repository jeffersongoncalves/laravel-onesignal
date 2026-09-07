<?php

use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\LaravelOnesignal\Facades\OneSignal;

it('lists templates', function () {
    Http::fake(['api.onesignal.com/api/v1/templates*' => Http::response(['templates' => []])]);

    OneSignal::templates()->list(10, 0);

    Http::assertSent(fn ($request) => $request->url() === 'https://api.onesignal.com/api/v1/templates?app_id=test-app-id&limit=10&offset=0');
});

it('gets a single template', function () {
    Http::fake(['api.onesignal.com/api/v1/templates/t1*' => Http::response(['id' => 't1'])]);

    OneSignal::templates()->get('t1');

    Http::assertSent(fn ($request) => $request->url() === 'https://api.onesignal.com/api/v1/templates/t1?app_id=test-app-id');
});

it('creates a template', function () {
    Http::fake(['api.onesignal.com/api/v1/templates' => Http::response(['id' => 't1'])]);

    OneSignal::templates()->create('Welcome', message: 'Hi there', heading: 'Welcome!');

    Http::assertSent(fn ($request) => $request['name'] === 'Welcome'
        && $request['contents'] === ['en' => 'Hi there']
        && $request['headings'] === ['en' => 'Welcome!']);
});
