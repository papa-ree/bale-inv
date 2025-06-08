<?php

namespace Paparee\BaleInv;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class BaleInvServiceProvider extends PackageServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../database/migrations' => base_path('database/migrations'),
        ], 'bale-inv-migrations');
    }

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('bale-inv')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_bale_inv_table');
    }
}
