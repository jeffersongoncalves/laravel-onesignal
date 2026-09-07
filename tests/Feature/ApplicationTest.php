<?php

use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\LaravelOnesignal\Facades\OneSignal;

it('gets the app', function () {
    Http::fake(['api.onesignal.com/api/v1/apps/test-app-id' => Http::response(['id' => 'test-app-id', 'name' => 'My App'])]);

    $result = OneSignal::app()->get();

    expect($result['name'])->toBe('My App');
});
