# Laravel Angular Artisan Generators

Angular generators for Artisan. Originally created at [laravel5-angular-material-starter](https://github.com/jadjoubran/laravel5-angular-material-starter).


# Usage

```
artisan ng:feature name    #New feature inside angular/app/
artisan ng:dialog name     #New custom dialog inside angular/dialogs/
artisan ng:directive name  #New directive inside angular/directives/
artisan ng:service name    #New service inside angular/services/
artisan ng:filter name     #New filter inside angular/filters/
artisan ng:config name     #New config inside angular/config/
```

# Installation

If you're using the starter project, then it's already pre-installed.

    composer require laravelangular/generators
    
    //and then add the provider in config/app.php
    LaravelAngular\Generators\LaravelServiceProvider::class,
    
    php artisan vendor:publish


# Contributors

Originally created at [laravel5-angular-material-starter](https://github.com/jadjoubran/laravel5-angular-material-starter) then moved to a separate package by [@m33ch](https://github.com/m33ch)


# Notes

- Do not append the word `service` or `controller`, they will be automatically added for you.
