<?php

namespace JeffersonGoncalves\LaravelOnesignal;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelOnesignalServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-onesignal')
            ->hasConfigFile();
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(OneSignal::class, function () {
            return new OneSignal(
                (string) config('laravel-onesignal.app_id'),
                (string) config('laravel-onesignal.rest_api_key'),
            );
        });
    }
}
