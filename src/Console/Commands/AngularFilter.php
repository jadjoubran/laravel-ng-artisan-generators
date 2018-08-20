<?php

namespace LaravelAngular\Generators\Console\Commands;

use File;
use Illuminate\Console\Command;

class AngularFilter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ng:filter {name}
    {--no-spec : Don\'t create a test file}
    {--no-import : Don\'t auto import in index.filters}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new filter in angular/filters';

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

        $js = file_get_contents(__DIR__.'/Stubs/AngularFilter/filter.js.stub');

        $js = str_replace('{{StudlyName}}', $studly_name, $js);

        $folder = base_path(config('generators.source.root')).'/'.config('generators.source.filters');

        if(!File::exists($folder))
            File::makeDirectory($folder);
        
        //create filter (.js)
        if(!File::exists($folder.'/'.$name.config('generators.suffix.filter')))
            File::put($folder.'/'.$name.config('generators.suffix.filter'), $js);
        else{
            $this->info('Filter already exists.');
            return false;
        }

        //import filter
        $filters_index = base_path(config('generators.source.root')).'/index.filters.js';
        
        if(!config('generators.angular_modules.filters.standalone'))
            $module = "angular.module('".config('generators.angular_modules.root')."')";
        else
            $module = "angular.module('"
                      .(config('generators.angular_modules.filters.use_prefix') ? config('generators.angular_modules.filters.prefix')."." : "")
                      .config('generators.angular_modules.filters.suffix')
                      ."', [])";

        if(!file_exists($filters_index)){
            File::put($filters_index, $module);
        }

        if (config('generators.misc.auto_import') && !$this->option('no-import')) {
            $filters = file_get_contents($filters_index);
            $filterName = lcfirst($studly_name);
            $newFilters = "\r\n\t.filter('$filterName', {$studly_name}Filter)";
            $filters = str_replace($module, $module.$newFilters, $filters);
            $filters = 'import {'.$studly_name."Filter} from './filters/{$name}.filter';\n".$filters;
            file_put_contents($filters_index, $filters);
        }

        $this->info('Filter created successfully.');
    }
}
