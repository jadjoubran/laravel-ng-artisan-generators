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
    protected $signature = 'ng:directive {name}';

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
        $directive_name = strtolower(substr($studly_name, 0, 1)).substr($studly_name, 1);

        $html = file_get_contents(__DIR__.'/Stubs/AngularDirective/directive.html.stub');
        $js = file_get_contents(__DIR__.'/Stubs/AngularDirective/directive.js.stub');
        $less = file_get_contents(__DIR__.'/Stubs/AngularDirective/directive.less.stub');

        $js = str_replace('{{StudlyName}}', $studly_name, $js);
        $js = str_replace('{{name}}', $name, $js);
        $js = str_replace('{{directiveName}}', $directive_name, $js);

        $folder = base_path(config('generators.source.main')).'/'.config('generators.source.directives').'/'.$name;
        if (is_dir($folder)) {
            $this->info('Folder already exists');

            return false;
        }

        //create folder
        File::makeDirectory($folder, 0775, true);

        //create view (.html)
        File::put($folder.'/'.$name.'.html', $html);

        //create directive (.js)
        File::put($folder.'/'.$name.config('generators.prefixFileNames.directive'), $js);

        //create less file (.less)
        File::put($folder.'/'.$name.'.less', $less);

        $this->info('Directive created successfully.');
    }
}
