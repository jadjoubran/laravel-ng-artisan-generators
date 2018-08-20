<?php

namespace LaravelAngular\Generators\Console\Commands;

use File;
use Illuminate\Console\Command;

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
        $ng_directive = str_replace('_', '-', $name);

        $js = file_get_contents(__DIR__.'/Stubs/AngularDirective/directive.js.stub');
        $spec = file_get_contents(__DIR__.'/Stubs/AngularDirective/directive.spec.js.stub');

        $js = str_replace('{{StudlyName}}', $studly_name, $js);
        $js = str_replace('{{name}}', $name, $js);

        $spec = str_replace('{{ng-directive}}', $ng_directive, $spec);

        $folder = base_path(config('generators.source.root')).'/'.config('generators.source.directives');
        
        if (! File::exists($folder)) 
            File::makeDirectory($folder, 0775, true);

        $spec_folder = base_path(config('generators.tests.source.root')).'/'.config('generators.tests.source.directives');

        //create directive (.directive.js)
        if(!File::exists($folder.'/'.$name.config('generators.suffix.directive')))
            File::put($folder.'/'.$name.config('generators.suffix.directive'), $js);
        else{
            $this->info('Directive already exists.');
            return false;
        }

        if (!$this->option('no-spec') && config('generators.tests.enable.directives')) {
            //create spec folder
            if (!File::exists($spec_folder)) {
                File::makeDirectory($spec_folder, 0775, true);
            }
            //create spec file (.directive.spec.js)
            File::put($spec_folder.'/'.$name.'.directive.spec.js', $spec);
        }

        //import directive
        $directives_index = base_path(config('generators.source.root')).'/index.directives.js';
        
        if(!config('generators.angular_modules.directives.standalone'))
            $module = "angular.module('".config('generators.angular_modules.root')."')";
        else
            $module = "angular.module('"
                      .(config('generators.angular_modules.directives.use_prefix') ? config('generators.angular_modules.directives.prefix')."." : "")
                      .config('generators.angular_modules.directives.suffix')
                      ."', [])";

        if(!file_exists($directives_index))
            File::put($directives_index, $module);

        if (config('generators.misc.auto_import') && !$this->option('no-import')) {
            $directives = file_get_contents($directives_index);
            $directiveName = lcfirst($studly_name);
            $newDirective = "\r\n\t.directive('$directiveName', {$studly_name}Directive)";
            $directives = str_replace($module, $module.$newDirective, $directives);
            $directives = 'import {'.$studly_name."Directive} from './directives/{$name}/{$name}.directive';\n".$directives;
            file_put_contents($directives_index, $directives);
        }

        $this->info('Directive created successfully.');
    }
}
