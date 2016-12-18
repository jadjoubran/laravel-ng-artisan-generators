<?php

namespace LaravelAngular\Generators\Console\Commands;

use File;
use Illuminate\Console\Command;

class AngularComponent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ng:component {name : can include subdir e.g. admin/name result admin/admin-name/admin-name.component.js or see no-prefix}
    {--no-spec : Don\'t create a test file}
    {--no-import : Don\'t auto import in index.components}
    {--no-prefix : Don\'t prefix component name after subdir e.g. <subdir>/name result <subdir>/name/name.component.js}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new component in angular/components';

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
        // Gets the argument and split it into an array
        $pathArray = explode('/', $this->argument('name'));
        // Gets the last element of the array, that it should be the name of the component
        $name = end($pathArray);
        // Deletes the last element of the Array (we store it in the variable $name)
        array_pop($pathArray);
        // Prefixes component after subdirectory path that contains it, to help avoid duplicated component names
        // e.g. components/admin/dashboard/dashboard.component.js
        //      components/users/dashboard/dashboard.component.js <-- duplicated to admin's dashboard component and will fail on import
        //  to: components/admin/admin-dashboard/admin-dashboard.component.js
        //  or: components/users/users-dashboard/users-dashboard.component.js <-- conveniently prefixed won't fail on import
        if (!$this->option('no-prefix')) {
            $prefix = '';
            foreach ($pathArray as $value) {
                $prefix = $prefix.$value.'-';
            }
            $name = $prefix.$name;
        }
        // Initialize the variable $path with a '/' cause if the array <= 0 it means there's no path, and we should use the deafult route
        $path = '/';
        // We iterate through the array to concatenat it again, adding always a '/' at the end of each array element
        foreach ($pathArray as $value) {
            $path = $path.$value.'/';
        }
        $studly_name = studly_case($name);
        $ng_component = str_replace('_', '-', $name);
        $html = file_get_contents(__DIR__.'/Stubs/AngularComponent/component.html.stub');
        $js = file_get_contents(__DIR__.'/Stubs/AngularComponent/component.js.stub');
        $style = file_get_contents(__DIR__.'/Stubs/AngularComponent/component.style.stub');
        $spec = file_get_contents(__DIR__.'/Stubs/AngularComponent/component.spec.js.stub');
        $js = str_replace('{{StudlyName}}', $studly_name, $js);
        $js = str_replace('{{name}}', $name, $js);
        $js = str_replace('{{path}}', $path, $js);
        $spec = str_replace('{{ng-component}}', $ng_component, $spec);
        $folder = base_path(config('generators.source.root')).'/'.config('generators.source.components').$path.$name;
        if (is_dir($folder)) {
            $this->info('Folder already exists');

            return false;
        }
        $spec_folder = base_path(config('generators.tests.source.root')).'/'.config('generators.tests.source.components');
        //create folder
        File::makeDirectory($folder, 0775, true);
        //create view (.component.html)
        File::put($folder.'/'.$name.config('generators.suffix.componentView'), $html);
        //create component (.component.js)
        File::put($folder.'/'.$name.config('generators.suffix.component'), $js);
        //create style file
        File::put($folder.'/'.$name.'.'.config('generators.suffix.stylesheet', 'scss'), $style);
        if (!$this->option('no-spec') && config('generators.tests.enable.components')) {
            //create spec folder
            if (!File::exists($spec_folder)) {
                File::makeDirectory($spec_folder, 0775, true);
            }
            //create spec file (.component.spec.js)
            File::put($spec_folder.'/'.$name.'.component.spec.js', $spec);
        }
        //import component
        $components_index = base_path(config('generators.source.root')).'/index.components.js';
        if (config('generators.misc.auto_import') && !$this->option('no-import') && file_exists($components_index)) {
            $components = file_get_contents($components_index);
            $componentName = lcfirst($studly_name);
            $newComponent = "\r\n\t.component('$componentName', {$studly_name}Component)";
            $module = "angular.module('app.components')";
            $components = str_replace($module, $module.$newComponent, $components);
            $components = 'import {'.$studly_name."Component} from './app/components{$path}{$name}/{$name}.component';\n".$components;
            file_put_contents($components_index, $components);
        }
        $this->info('Component created successfully.');
    }
}
