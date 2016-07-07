<?php

namespace LaravelAngular\Generators\Console\Commands;

use File;
use Illuminate\Console\Command;

class AngularPage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ng:page {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new page in angular/app/pages';

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

        //Gets the argument and split it into an array
        $pathArray = explode('/', $this->argument('name'));

        //Gets the last element of the array, that it should be the name of the page
        $name = end($pathArray);

        //Deletes the last element of the Array (we store it in the variable $name)
        array_pop($pathArray);

        //Initialize the variable $path with a '/' cause if the array <= 0 it means there's no path, and we should use the default route
        $path = '/';

        //We iterate trought the array to concatenate it again, adding always a '/' at the end of each array element
        foreach ($pathArray as $value) {
            $path = $path.$value.'/';
        }

        $html = file_get_contents(__DIR__.'/Stubs/AngularPage/page.html.stub');
        $style = file_get_contents(__DIR__.'/Stubs/AngularPage/page.style.stub');

        $folder = base_path(config('generators.source.root')).'/'.config('generators.source.page').$path.$name;

        if (is_dir($folder)) {
            $this->info('Folder already exists');

            return false;
        }

        //create folder
        File::makeDirectory($folder, 0775, true);

        //create view (.page.html)
        File::put($folder.'/'.$name.config('generators.suffix.pageView'), $html);

        //create style file
        File::put($folder.'/'.$name.'.'.config('generators.suffix.stylesheet', 'scss'), $style);

        $this->info('Page created successfully.');
    }
}
