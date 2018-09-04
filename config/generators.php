<?php

return [
      'source' => [
            'root'             => 'angular',
            'pages'             => 'app/pages',
            'components'       => 'app/components',
            'directives'       => 'directives',
            'config'           => 'config',
            'dialogs'          => 'dialogs',
            'filters'          => 'filters',
            'run'              => 'run',
            'services'         => 'services',
      ],
      'suffix' => [
            'components' => [
                  'html'       => '.component.html',
                  'js'         => '.component.js',
                  'stylesheet' => '.scss', // less, scss or css
            ],
            'directives' => '.directive.js',
            'services'   => '.service.js',
            'config'     => '.config.js',
            'filters'    => '.filter.js',
            'dialogs'    => [
                  'html' => '.dialog.html',
                  'js'   => '.dialog.js',
            ],
            'pages'      => [
                  'html'       => '.page.html',
                  'stylesheet' => '.scss', // less, scss or css
            ],
            'run'        => '.run.js'
      ],
      'tests' => [
            'enable' => [
                'components'   => false,
                'services'     => false,
                'directives'   => false,
            ],
            'source' => [
                'root'         => 'tests/angular',
                'components'   => 'app/components',
                'directives'   => 'directives',
                'services'     => 'services',
            ],
      ],
      'misc' => [
            'auto_import'      => true,
            'use_mix'          => true,
      ],
      'angular_modules' => [
            'root'       => 'app',
            'components' => [
                  'standalone' => true,
                  'use_prefix' => true,
                  'prefix'     => 'app',
                  'suffix'     => 'components',
            ],
            'directives' => [
                  'standalone' => true,
                  'use_prefix' => true,
                  'prefix'     => 'app',
                  'suffix'     => 'directives',
            ],
            'config' => [
                  'standalone' => true,
                  'use_prefix' => true,
                  'prefix'     => 'app',
                  'suffix'     => 'config',
            ],
            'filters' => [
                  'standalone' => true,
                  'use_prefix' => true,
                  'prefix'     => 'app',
                  'suffix'     => 'filters',
            ],
            'run' => [
                  'standalone' => true,
                  'use_prefix' => true,
                  'prefix'     => 'app',
                  'suffix'     => 'run',
            ],
            'services' => [
                  'standalone' => true,
                  'use_prefix' => true,
                  'prefix'     => 'app',
                  'suffix'     => 'services',
            ],
      ]
];
