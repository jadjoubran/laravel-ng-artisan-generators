<?php

namespace LaravelAngular\Generators\Console\Commands;

use File;
use Illuminate\Console\Command;
use LaravelAngular\Generators\Utils;

class AngularDialog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ng:dialog {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new dialog in angular/dialog';

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
        $name = $this->argument('name');
        $studly_name = studly_case($name);
        $human_readable = ucfirst(str_replace('_', ' ', $name));

        $config = Utils::getConfig('dialogs', false);

        $files = [
            'templates' => [
                [
                    'template' => 'Stubs::AngularDialog.html',
                    'vars'     => [
                        'human_redable' => $human_readable,
                    ],
                    'path'     => $config['path'].'/'.$name,
                    'name'     => $name.$config['suffix']['html'],
                ],
                [
                    'template' => 'Stubs::AngularDialog.js',
                    'vars'     => [
                        'studly_name' => $studly_name,
                    ],
                    'path'     => $config['path'].'/'.$name,
                    'name'     => $name.$config['suffix']['js'],
                ],
            ],
        ];

        if(! Utils::createFiles($files, false, true)) {
            $this->info('Dialog already exists.');
            return false;
        }

        $this->info('Dialog created successfully.');
    }
}
