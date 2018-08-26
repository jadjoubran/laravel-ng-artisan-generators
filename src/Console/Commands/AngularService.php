<?php

namespace LaravelAngular\Generators\Console\Commands;

use File;
use Illuminate\Console\Command;
use LaravelAngular\Generators\Utils;

class AngularService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ng:service {name}
    {--no-spec : Don\'t create a test file}
    {--no-import : Don\'t auto import in index.services}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new service in angular/services';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
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

        $config = Utils::getConfig('services', true);

        $files = [
            'templates' => [
                [
                    'template' => 'Stubs::AngularService.js',
                    'vars'     => [
                        'studly_name' => $studly_name,
                    ],
                    'path'     => $config['path'],
                    'name'     => $name.$config['suffix'],
                ],
            ],
            'spec'      => [
                'template' => 'Stubs::AngularService.test',
                'vars'     => [
                    'studly_name' => $studly_name,
                ],
                'path'     => $config['spec_path'],
                'name'     => $name.'.component.spec.js',
            ],
        ];

        if(! Utils::createFiles($files, !$this->option('no-spec') && $config['enable_test'], false)) {
            $this->info("Service already exists.");
            return false;
        }

        if(!$this->option('no-import') && $config['auto_import']) {
            Utils::import('services', $name, rtrim($config['suffix'], ".js")); // import component
        }


        $this->info('Service created successfully.');
    }
}
