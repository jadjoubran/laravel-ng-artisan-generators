<?php

namespace LaravelAngular\Generators\Console\Commands;

use File;
use Illuminate\Console\Command;

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

        $js = file_get_contents(__DIR__.'/Stubs/AngularConfig/config.js.stub');

        $js = str_replace('{{StudlyName}}', $studly_name, $js);

        $folder = base_path(config('generators.source.root')).'/'.config('generators.source.config');

        if(!File::exists($folder))
            File::makeDirectory($folder, 0775, true);
        
        //create config (.js)
        if(!File::exists($folder.'/'.$name.config('generators.suffix.config')))
            File::put($folder.'/'.$name.config('generators.suffix.config'), $js);
        else{
            $this->info('Config already exists.');
            return false;
        }

        //import config
        $config_index = base_path(config('generators.source.root')).'/index.config.js';
        
        if(!config('generators.angular_modules.config.standalone'))
            $module = "angular.module('".config('generators.angular_modules.root')."')";
        else
            $module = "angular.module('"
                      .(config('generators.angular_modules.config.use_prefix') ? config('generators.angular_modules.config.prefix')."." : "")
                      .config('generators.angular_modules.config.suffix')
                      ."', [])";
                      
        if(!file_exists($config_index))
            File::put($config_index, $module);

        if (config('generators.misc.auto_import') && !$this->option('no-import')) {
            $configs = file_get_contents($config_index);
            $newConfig = "\r\n\t.config({$studly_name}Config)";
            $configs = str_replace($module, $module.$newConfig, $configs);
            $configs = 'import {'.$studly_name."Config} from './config/{$name}.config';\n".$configs;
            file_put_contents($config_index, $configs);
        }

        $this->info('Config created successfully.');
    }
}
