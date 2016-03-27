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
    protected $signature = 'ng:component {name} {--no-spec : Don\'t create a test file}';

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
        $name = $this->argument('name');
        $studly_name = studly_case($name);
        $ng_component = str_replace('-', '-', $name);

        $html = file_get_contents(__DIR__.'/Stubs/AngularComponent/component.html.stub');
        $js = file_get_contents(__DIR__.'/Stubs/AngularComponent/component.js.stub');
        $less = file_get_contents(__DIR__.'/Stubs/AngularComponent/component.less.stub');
        $spec = file_get_contents(__DIR__.'/Stubs/AngularComponent/component.spec.js.stub');

        $js = str_replace('{{StudlyName}}', $studly_name, $js);
        $js = str_replace('{{name}}', $name, $js);

        $spec = str_replace('{{ng-component}}', $ng_component, $spec);

        $folder = base_path(config('generators.source.root')).'/'.config('generators.source.components').'/'.$name;
        if (is_dir($folder)) {
            $this->info('Folder already exists');

            return false;
        }

        $spec_folder = base_path(config('generators.tests.source.root')).'/'.config('generators.tests.source.components');

        //create folder
        File::makeDirectory($folder, 0775, true);

        //create view (.component.html)
        File::put($folder.'/'.$name.config('generators.prefix.componentView'), $html);

        //create component (.component.js)
        File::put($folder.'/'.$name.config('generators.prefix.component'), $js);

        //create less file (.less)
        File::put($folder.'/'.$name.'.less', $less);

        if (!$this->option('no-spec') && config('generators.tests.enable.components')) {
            //create spec file (.component.spec.js)
            File::put($spec_folder.'/'.$name.'.component.spec.js', $spec);
        }

        $this->info('Component created successfully.');
    }
}
