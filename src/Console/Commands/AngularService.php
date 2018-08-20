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

        $js = file_get_contents(__DIR__.'/Stubs/AngularService/service.js.stub');
        $spec = file_get_contents(__DIR__.'/Stubs/AngularService/service.spec.js.stub');

        $js = str_replace('{{StudlyName}}', $studly_name, $js);
        $spec = str_replace('{{StudlyName}}', $studly_name, $spec);

        $folder = base_path(config('generators.source.root')).'/'.config('generators.source.services');

        $spec_folder = base_path(config('generators.tests.source.root')).'/'.config('generators.tests.source.services');

        if(!File::exists($folder))
            File::makeDirectory($folder, 0775, true);
        
        //create service (.service.js)
        if(!File::exists($folder.'/'.$name.config('generators.suffix.service')))
            File::put($folder.'/'.$name.config('generators.suffix.service'), $js);
        else{
            $this->info('Service already exists.');
            return false;
        }

        if (!$this->option('no-spec') && config('generators.tests.enable.services')) {
            //create spec folder
            if (!File::exists($spec_folder)) {
                File::makeDirectory($spec_folder, 0775, true);
            }
            //create spec (.service.spec.js)
            File::put($spec_folder.'/'.$name.'.service.spec.js', $spec);
        }

        //import service
        $services_index = base_path(config('generators.source.root')).'/index.services.js';

        if(!config('generators.angular_modules.services.standalone'))
            $module = "angular.module('".config('generators.angular_modules.root')."')";
        else
            $module = "angular.module('"
                      .(config('generators.angular_modules.services.use_prefix') ? config('generators.angular_modules.services.prefix')."." : "")
                      .config('generators.angular_modules.services.suffix')
                      ."', [])";

        if(!file_exists($services_index)){
            File::put($services_index, $module);
        }

        if (config('generators.misc.auto_import') && !$this->option('no-import')) {
            $services = file_get_contents($services_index);
            $newService = "\r\n\t.service('{$studly_name}Service', {$studly_name}Service)";
            $services = str_replace($module, $module.$newService, $services);
            $services = 'import {'.$studly_name."Service} from './services/{$name}.service';\n".$services;
            file_put_contents($services_index, $services);
        }

        $this->info('Service created successfully.');
    }
}
