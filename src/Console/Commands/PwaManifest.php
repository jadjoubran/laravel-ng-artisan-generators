<?php

namespace LaravelAngular\Generators\Console\Commands;

use Illuminate\Console\Command;

class PwaManifest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pwa:manifest';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate manifest.json';

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
        $manifest['lang'] = $this->ask('Enter the language', 'en-us');
        $manifest['name'] = $this->ask('Enter the name of your app', 'Laravel & Angular');
        $manifest['short_name'] = $this->ask('Enter the short name of your app', 'Laravel & Angular');
        $manifest['display'] = $this->anticipate('Choose a display type? [fullscreen|standalone|minimal-ui|browser]', ['fullscreen', 'standalone', 'minimal-ui', 'browser'], 'standalone');
        $manifest['start_url'] = $this->ask('Enter the start url', '/?homescreen=1');
        $manifest['background_color'] = $this->ask('Enter a background color: ', '#ffffff');
        $manifest['theme_color'] = $this->ask('Enter your theme color', '#0690B7');

        $manifest['icons'] = [[
                'src'   => 'img/icon.png',
                'sizes' => '198x198',
                'type'  => 'image/png',
            ]];

        $output = json_encode($manifest, JSON_PRETTY_PRINT);

        file_put_contents('public/manifest.json', $output);

        $this->info("Manifest generated successfully.\r\nDon't forget to update your icon in public/img/icon.png");
    }
}
