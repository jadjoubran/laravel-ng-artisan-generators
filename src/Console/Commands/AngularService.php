<?php

namespace LaravelAngular\Generators\Console\Commands;

use File;
use Illuminate\Console\Command;

class AngularService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ng:service {name} {--no-spec : Don\'t create a test file}';

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

        $js = file_get_contents(__DIR__.'/Stubs/AngularService/service.js.stub');
        $spec = file_get_contents(__DIR__.'/Stubs/AngularService/service.spec.js.stub');

        $js = str_replace('{{StudlyName}}', $studly_name, $js);
        $spec = str_replace('{{StudlyName}}', $studly_name, $spec);

        $folder = base_path(config('generators.source.root')).'/'.config('generators.source.services');

        $spec_folder = base_path(config('generators.tests.source.root')).'/'.config('generators.tests.source.services');

        //create service (.service.js)
        File::put($folder.'/'.$name.config('generators.prefix.service'), $js);

        if (!$this->option('no-spec') && config('generators.tests.enable.services')){
            //create spec (.service.spec.js)
            File::put($spec_folder.'/'.$name.'.service.spec.js', $spec);
        }

        $this->info('Service created successfully.');
    }
}
