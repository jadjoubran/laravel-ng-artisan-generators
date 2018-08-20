<?php

return [
      'source' => [
            'root'             => 'angular',
            'page'             => 'app/pages',
            'components'       => 'app/components',
            'directives'       => 'directives',
            'config'           => 'config',
            'dialogs'          => 'dialogs',
            'filters'          => 'filters',
            'services'         => 'services',
      ],
      'suffix' => [
            'component'        => '.component.js',
            'componentView'    => '.component.html',
            'dialog'           => '.dialog.js',
            'dialogView'       => '.dialog.html',
            'directive'        => '.directive.js',
            'service'          => '.service.js',
            'config'           => '.config.js',
            'filter'           => '.filter.js',
            'pageView'         => '.page.html',
            'stylesheet'       => 'scss', // less, scss or css
      ],
      'tests' => [
            'enable' => [
                'components'   => true,
                'services'     => true,
                'directives'   => true,
            ],
            'source' => [
                'root'         => 'tests/angular/',
                'components'   => 'app/components',
                'directives'   => 'directives',
                'services'     => 'services',
            ],
      ],
      'misc' => [
            'auto_import'      => true,
      ],
      'angular_modules' => [
            'root' => 'app',
            'components' => [
                  'standalone' => true,
                  'use_prefix' => true,
                  'prefix'     => 'app',
                  'suffix'     => 'components'
            ],
            'directives' => [
                  'standalone' => true,
                  'use_prefix' => true,
                  'prefix'     => 'app',
                  'suffix'     => 'directives'
            ],
            'config' => [
                  'standalone' => true,
                  'use_prefix' => true,
                  'prefix'     => 'app',
                  'suffix'     => 'config'
            ],
            'filters' => [
                  'standalone' => true,
                  'use_prefix' => true,
                  'prefix'     => 'app',
                  'suffix'     => 'filters'
            ],
            'services' => [
                  'standalone' => true,
                  'use_prefix' => true,
                  'prefix'     => 'app',
                  'suffix'     => 'services'
            ],
      ]
];
