<?php

namespace LaravelAngular\Generators;

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
            __DIR__.'/../config/generators.php' => config_path('generators.php'),
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([
            'LaravelAngular\Generators\Console\Commands\AngularConfig',
            'LaravelAngular\Generators\Console\Commands\AngularDialog',
            'LaravelAngular\Generators\Console\Commands\AngularComponent',
            'LaravelAngular\Generators\Console\Commands\AngularDirective',
            'LaravelAngular\Generators\Console\Commands\AngularPage',
            'LaravelAngular\Generators\Console\Commands\AngularFilter',
            'LaravelAngular\Generators\Console\Commands\AngularService',
            'LaravelAngular\Generators\Console\Commands\PwaManifest',
        ]);

        $this->mergeConfigFrom(__DIR__.'/../config/generators.php', 'generators');
    }
}
