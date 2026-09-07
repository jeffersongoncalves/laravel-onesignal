<?php

use JeffersonGoncalves\LaravelOnesignal\Facades\OneSignal as OneSignalFacade;
use JeffersonGoncalves\LaravelOnesignal\OneSignal;

it('registers the onesignal singleton', function () {
    expect(app(OneSignal::class))->toBeInstanceOf(OneSignal::class);
});

it('resolves the facade to the onesignal class', function () {
    expect(OneSignalFacade::getFacadeRoot())->toBeInstanceOf(OneSignal::class);
});

it('merges the config file', function () {
    expect(config('laravel-onesignal.app_id'))->toBe('test-app-id');
    expect(config('laravel-onesignal.rest_api_key'))->toBe('test-rest-api-key');
});
