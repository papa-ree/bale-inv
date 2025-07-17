<?php

namespace Paparee\BaleInv;

use Paparee\BaleInv\Commands\UpdateInvMigrationsCommand;
use Paparee\BaleInv\Commands\UpdateViewCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class BaleInvServiceProvider extends PackageServiceProvider
{

    public function register()
    {
        $this->app->bind('command.bale-inv:update-view', UpdateViewCommand::class);
        $this->app->bind('command.bale-inv:update-migration', UpdateInvMigrationsCommand::class);
        
        $this->commands([
            'command.bale-inv:update-view',
            'command.bale-inv:update-migration',
        ]);
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../database/migrations/inv' => base_path('database/migrations/inv'),
        ], 'bale-inv-migrations');

        $this->publishes([
            __DIR__.'/../resources/views/livewire' => resource_path('views/livewire'),
        ], 'bale-inv-views');
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
