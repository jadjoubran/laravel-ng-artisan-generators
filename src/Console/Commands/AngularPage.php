<?php

namespace LaravelAngular\Generators\Console\Commands;

use File;
use Illuminate\Console\Command;
use LaravelAngular\Generators\Utils;

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

        $name = $this->argument("name");

        $config = Utils::getConfig('pages', false);

        if(File::exists($config['path'].'/'.$name)) {
            $this->info("Page already exists.");
            return false;
        } else {
            File::makeDirectory($config['path'].'/'.$name, 0775, true);
        }

        File::put($config['path'].'/'.$name.'/'.$name.$config['suffix']['html'], '');
        File::put($config['path'].'/'.$name.'/'.$name.$config['suffix']['stylesheet'], '');

        $this->info('Page created successfully.');
    }
}
