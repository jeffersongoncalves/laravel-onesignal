<?php

use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\LaravelOnesignal\Exceptions\OneSignalException;
use JeffersonGoncalves\LaravelOnesignal\Facades\OneSignal;

it('sends the rest api key as a literal basic authorization header', function () {
    Http::fake(['api.onesignal.com/*' => Http::response(['id' => 'app'])]);

    OneSignal::app()->get();

    Http::assertSent(fn ($request) => $request->header('Authorization') === ['Basic test-rest-api-key']);
});

it('throws an OneSignalException with the decoded error body on failure', function () {
    Http::fake(['api.onesignal.com/*' => Http::response(['errors' => ['Invalid app_id']], 400)]);

    OneSignal::app()->get();
})->throws(OneSignalException::class, 'Invalid app_id');
