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
    protected $signature = 'ng:config {name}';

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

        $js = file_get_contents(__DIR__.'/Stubs/AngularConfig/config.js.stub');

        $folder = base_path(config('ng.source')).'/'.config('ng.config').'/';

        //create config (.js)
        File::put($folder.'/'.$name.'.js', $js);

        $this->info('Config created successfully.');
    }
}
