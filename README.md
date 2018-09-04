# Laravel AngularJS Artisan Generators

AngularJS generators for Artisan. Originally created at [laravel5-angular-material-starter](https://github.com/jadjoubran/laravel5-angular-material-starter).


# Installation

If you're using the starter project, then it's already pre-installed.

    composer require laravelangular/generators

    //and then add the provider in config/app.php
    LaravelAngular\Generators\LaravelServiceProvider::class,

    php artisan vendor:publish


# Usage

```shell
php artisan ng:component name [--no-import] [--no-spec]        #New component inside angular/app/components/
php artisan ng:config name [--no-import]                       #New config inside angular/config/
php artisan ng:dialog name                                     #New custom dialog inside angular/dialogs/
php artisan ng:directive name [--no-import] [--no-spec]        #New directive inside angular/directives/
php artisan ng:filter name [--no-import]                       #New filter inside angular/filters/
php artisan ng:page name                                       #New page inside angular/app/pages/
php artisan ng:run name [--no-import]                          #New run function inside angular/run
php artisan ng:service name [--no-import] [--no-spec]          #New service inside angular/services/
php artisan ng:import [<type>...] [--all | -a] [--ignore | -i] #Reimport all in index files
```

These commands will create new directories and files for AngularJS front-end in new ES6 syntax. 
If not present, commands will create index files (i.e.: `index.components.js`) and, if enabled, new created classes will be imported.

Configurations are editable in `config\generators.php`. See below for details.


# Configuration

* **source**: name of directories. They make a path to new created files
   * **root**: name of the directory on where all created files and folders will be put.
   * Other entries indicate directories where files will be put. I.e running `php artisan ng:component name` will be created three new files for component `name` with `root/components/name/` path. Default is `angular/app/components/name/`
* **suffix**: name and extension appended to file name. I.e.: running `php artisan ng:directive name` will be created a file named `name.directive.js`.
   * **stylesheet**: extension for stylesheets. NOTE: Stylesheets are created for both pages and components
* **tests**
   * **enable**: whether to enable or disable creation of test files
   * **source**: same as `source`, but for test files
* **misc**
   * **auto_import**: enable or disable automatic import in index files.
   * **use_mix**: set this to true if you're using [Laravel Mix](https://laravel.com/docs/5.6/mix)
* **angular_modules**: configuration for angular root module and submodules. If index files are created before or manually, these settings will help recognize angular modules for automatic import. If index file is created on first command run, these settings will create angular module for you.
   * **root**: angular root module.
   * **standalone**: if a module is defined as standalone (i.e.: `angular.module('mymodule', [])`) or is part of a root module (`angular.module('mymodule')`). If set to false, `use_prefix`, `prefix` and `suffix` will be ignored and root module name will be used.
   * **prefix** and **suffix**: name of module of the type `prefix.suffix`; i.e.: `app.components`.
   * **use_prefix**: whether to use prefix for module name


# Options

## Common options
**no-import**: Disable import for created file.
<br>
**no-spec**: Disable creation of test file.

## Import options
**type**: Specify which type of file should be imported. I.e.: `php artisan ng:import components directives filters` will import all components, directives and filters in their respective index file.
<br>
**all**: Import all importable classes: components, configs, directives, filters, runs and services.
<br>
**ignore**: Set which files should not be imported; i.e: `php php artisan ng:import -i components -i directives` will import all importable classes except for components and directives.

# Documentation

[View Angular Generators documentation](https://laravel-angular.readme.io/docs/generators-intro)

# Contributors

Originally created at [laravel5-angular-material-starter](https://github.com/jadjoubran/laravel5-angular-material-starter) then moved to a separate package by [@m33ch](https://github.com/m33ch)


# Notes

- Do not append the word `service`, it will be automatically added for you.
