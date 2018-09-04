<?php

namespace LaravelAngular\Generators\Console\Commands;

use File;
use Illuminate\Console\Command;
use LaravelAngular\Generators\Utils;

class AngularDirective extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ng:directive {name}
    {--no-spec : Don\'t create a test file}
    {--no-import : Don\'t auto import in index.directives}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new directive in angular/directives';

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
        $ng_directive = strtolower(preg_replace("([A-Z])", "-$0", lcfirst($studly_name)));

        $config = Utils::getConfig('directives', true);

        $files = [
            'templates' => [
                [
                    'template' => 'Stubs::AngularDirective.js',
                    'vars'     => [
                        'studly_name' => $studly_name,
                    ],
                    'path'     => $config['path'],
                    'name'     => $name.$config['suffix'],
                ],
            ],
            'spec'      => [
                'template' => 'Stubs::AngularDirective.test',
                'vars'     => [
                    'ng_directive' => $ng_directive,
                ],
                'path'     => $config['spec_path'],
                'name'     => $name.'.component.spec.js',
            ],
        ];

        if(! Utils::createFiles($files, !$this->option('no-spec') && $config['enable_test'], false)) {
            $this->info("Directive already exists.");
            return false;
        }

        if(!$this->option('no-import') && $config['auto_import']) {
            Utils::import('directives', $name, rtrim($config['suffix'], ".js")); // import component
        }

        $this->info('Directive created successfully.');
    }
}
