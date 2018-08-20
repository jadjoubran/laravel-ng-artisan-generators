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
php artisan ng:page name       #New page inside angular/app/pages/
php artisan ng:component name  #New component inside angular/app/components/
php artisan ng:directive name  #New directive inside angular/directives/
php artisan ng:config name     #New config inside angular/config/
php artisan ng:dialog name     #New custom dialog inside angular/dialogs/
php artisan ng:filter name     #New filter inside angular/filters/
php artisan ng:service name    #New service inside angular/services/
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
* **misc.auto_import**: enable or disable automatic import in index files.
* **angular_modules**: configuration for angular root module and submodules. If index files are created before or manually, these settings will help recognize angular modules for automatic import. If index file is created on first command run, these settings will create angular module for you.
   * **root**: angular root module.
   * **standalone**: if a module is defined as standalone (i.e.: `angular.module('mymodule', [])`) or is part of a root module (`angular.module('mymodule')`). If set to false, `use_prefix`, `prefix` and `suffix` will be ignored and root module name will be used.
   * **prefix** and **suffix**: name of module of the type `prefix.suffix`; i.e.: `app.components`.
   * **use_prefix**: whether to use prefix for module name

# Documentation

[View Angular Generators documentation](https://laravel-angular.readme.io/docs/generators-intro)

# Contributors

Originally created at [laravel5-angular-material-starter](https://github.com/jadjoubran/laravel5-angular-material-starter) then moved to a separate package by [@m33ch](https://github.com/m33ch)


# Notes

- Do not append the word `service`, it will be automatically added for you.
