<?php

namespace LaravelAngular\Generators\Console\Commands;

use File;
use Illuminate\Console\Command;
use LaravelAngular\Generators\Utils;

class AngularConfig extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ng:config {name}
    {--no-spec : Don\'t create a test file}
    {--no-import : Don\'t auto import in index.config}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new config in angular/config';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        view()->replaceNamespace('Stubs', __DIR__.'/Stubs');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->argument('name');
        $studly_name = studly_case($name);

        $config = Utils::getConfig('config', false);

        $files = [
            'templates' => [
                [
                    'template' => 'Stubs::AngularConfig.js',
                    'vars'     => [
                        'studly_name' => $studly_name,
                    ],
                    'path'     => $config['path'],
                    'name'     => $name.$config['suffix'],
                ],
            ],
        ];

        if(! Utils::createFiles($files, false, false)) {
            $this->info('Config already exists.');
            return false;
        }

        if(!$this->option('no-import') && $config['auto_import']) {
            Utils::import('config', $name, rtrim($config['suffix'], ".js"));
        }

        $this->info('Config created successfully.');
    }
}
