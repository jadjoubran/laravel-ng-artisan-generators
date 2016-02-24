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
        $name = $this->argument('name');

        $html = file_get_contents(__DIR__.'/Stubs/AngularPage/page.html.stub');
        $less = file_get_contents(__DIR__.'/Stubs/AngularPage/page.less.stub');

        $folder = base_path(config('generators.source.root')).'/'.config('generators.source.page').'/'.$name;
        if (is_dir($folder)) {
            $this->info('Folder already exists');

            return false;
        }

        //create folder
        File::makeDirectory($folder, 0775, true);

        //create view (.page.html)
        File::put($folder.'/'.$name.config('generators.prefix.pageView'), $html);

        //create less file (.less)
        File::put($folder.'/'.$name.'.less', $less);

        $this->info('Page created successfully.');
    }
}
