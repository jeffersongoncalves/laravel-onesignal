<?php

namespace Jeffersongoncalves\LaravelOnesignal;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelOnesignalServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-onesignal')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigrations();
    }
}
