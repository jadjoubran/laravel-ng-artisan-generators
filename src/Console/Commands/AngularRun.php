<?php

namespace LaravelAngular\Generators\Console\Commands;

use File;
use Illuminate\Console\Command;
use LaravelAngular\Generators\Utils;

class AngularRun extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ng:run {name}
    {--no-import : Don\'t auto import in index.run}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new run in angular/run';

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

        $config = Utils::getConfig('run', false);

        $files = [
            'templates' => [
                [
                    'template' => 'Stubs::AngularRun.js',
                    'vars'     => [
                        'studly_name' => $studly_name,
                    ],
                    'path'     => $config['path'],
                    'name'     => $name.$config['suffix'],
                ],
            ],
        ];

        if(! Utils::createFiles($files, false, false)) {
            $this->info("Run already exists.");
            return false;
        }

        if(!$this->option('no-import') && $config['auto_import']) {
            Utils::import('run', $name, rtrim($config['suffix'], ".js")); // import component
        }


        $this->info('Run created successfully.');
    }
}
