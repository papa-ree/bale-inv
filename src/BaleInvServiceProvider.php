<?php

namespace Paparee\BaleInv;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class BaleInvServiceProvider extends PackageServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../src/App/Jobs' => app_path('Jobs'),
        ], 'bale-inv-jobs');

        $this->publishes([
            __DIR__.'/../database/migrations/inv' => base_path('database/migrations/inv'),
        ], 'bale-inv-migrations');

        $this->publishes([
            __DIR__.'/../resources/views/livewire' => resource_path('views/livewire'),
        ], 'bale-cms-views');
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
