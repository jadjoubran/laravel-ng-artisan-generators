<?php

namespace LaravelAngular\Generators\Console\Commands;

use File;
use LaravelAngular\Generators\Utils;
use Illuminate\Console\Command;

class Import extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ng:import {which?*} 
    {--a|all : Import all classes in index files} 
    {--i|ignore=* : Import all types except for specified list}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import classes in index files';

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
        $options = $this->options();
        $types = $this->argument('which');
        $allowed_types = array('components', 'directives', 'config', 'filters', 'run', 'services');

        $import;

        if(count($types)) {
            if($options['all']) {
                $error = '  Cannot pass type list with --all option. Use --ignore instead.  ';
                $this->error("\n".str_repeat(" ", strlen($error))."\n".$error."\n".str_repeat(" ", strlen($error))."\n");
                return false;
            }

            $import = array_intersect($types, $allowed_types);

        } else if(count($options['ignore']) && $options['ignore'][0]) {
            $ignore = array_intersect($options['ignore'], $allowed_types);
            
            $import = array_diff($allowed_types, $ignore);

        } else if($options['all']) {
            $import = $allowed_types;

        } else {
            $error = "  No arguments or options supplied.  ";
            $this->error("\n".str_repeat(" ", strlen($error))."\n".$error."\n".str_repeat(" ", strlen($error))."\n");
            return false;
        }

        $this->import($import);

        $this->info(ucfirst(implode(", ", $import)). " imported ");
        
    }

    private function import($types){
        foreach($types as $type) {
            $config = Utils::getConfig($type, false); // don't need test info, only base directory

            $names; 
            $suffix;
            
            if(File::exists($config['path'])) {
                if($type == 'components') {
                    $suffix = rtrim($config['suffix']['js'], ".js");
                    $names = File::directories($config['path']);

                } else {
                    $suffix = rtrim($config['suffix'], ".js");
                    $names = File::files($config['path']);
                }

                $refreshed = false;
                
                foreach($names as $name){
                    Utils::import($type, basename($name, $suffix.".js"), $suffix, !$refreshed);
                    $refreshed = true;
                }
            }
        }
    }
}