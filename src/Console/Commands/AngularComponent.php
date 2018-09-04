<?php

namespace LaravelAngular\Generators\Console\Commands;

use File;
use LaravelAngular\Generators\Utils;
use Illuminate\Console\Command;

class AngularComponent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ng:component {name}
    {--no-spec : Don\'t create a test file}
    {--no-import : Don\'t auto import in index.components}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new components in angular/components';

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

        // converts any strange name to correct markup
        // i.e.: my_AngularComponent -> my-angular-component
        $ng_component = strtolower(preg_replace("([A-Z])", "-$0", lcfirst($studly_name)));

        $config = Utils::getConfig('components', true);
        
        $files = [
            'templates' => [
                [
                    'template' => 'Stubs::AngularComponent.html',
                    'vars'     => [],
                    'path'     => $config['path'].'/'.$name,
                    'name'     => $name.$config['suffix']['html'],
                ],
                [
                    'template' => 'Stubs::AngularComponent.js',
                    'vars'     => [
                        'studly_name' => $studly_name, 
                        'name'        => $name,
                        'use_mix'     => $config['use_mix'],
                    ],
                    'path'     => $config['path'].'/'.$name,
                    'name'     => $name.$config['suffix']['js'],
                ],
                [
                    'template' => 'Stubs::AngularComponent.style',
                    'vars'     => [
                        'ng_component' => $ng_component,
                    ],
                    'path'     => $config['path'].'/'.$name,
                    'name'     => $name.$config['suffix']['stylesheet'],
                ],
            ],
            'spec' => [   
                'template' => 'Stubs::AngularComponent.test', 
                'vars'     => [
                    'ng_component' => $ng_component,
                ],
                'path'     => $config['spec_path'],
                'name'     => $name.'.component.spec.js',
            ],
        ];
        
        if(! Utils::createFiles($files, !$this->option('no-spec') && $config['enable_test'], true)) {
            $this->info('Component already exists.');
            return false;
        }

        if(!$this->option('no-import') && $config['auto_import']) {
            Utils::import('components', $name, rtrim($config['suffix']['js'], ".js")); // import component
        }

        $this->info('Component created successfully.');
    }
}
