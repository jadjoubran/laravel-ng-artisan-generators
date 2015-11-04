<?php

namespace Rovito\Laraplate\Providers;

use Illuminate\Support\ServiceProvider;

class LaravelServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('ng.php')
        ], 'config');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([
            'Rovito\Laraplate\Console\Commands\AngularConfig',
            'Rovito\Laraplate\Console\Commands\AngularDialog',
            'Rovito\Laraplate\Console\Commands\AngularDirective',
            'Rovito\Laraplate\Console\Commands\AngularFeature',
            'Rovito\Laraplate\Console\Commands\AngularFilter',
            'Rovito\Laraplate\Console\Commands\AngularService',
        ]);

        $this->mergeConfigFrom(__DIR__ . '/../Config/config.php', 'ng');
    }
}
