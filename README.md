# Laravel Angular Artisan Generators

Angular generators for Artisan. Originally created at [laravel5-angular-material-starter](https://github.com/jadjoubran/laravel5-angular-material-starter).


# Usage

```
artisan ng:page name       #New page inside angular/app/pages/
artisan ng:dialog name     #New custom dialog inside angular/dialogs/
artisan ng:component name  #New component inside angular/app/components/
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


# Documentation

[View Angular Generators documentation](https://laravel-angular.readme.io/docs/generators-intro)

# Contributors

Originally created at [laravel5-angular-material-starter](https://github.com/jadjoubran/laravel5-angular-material-starter) then moved to a separate package by [@m33ch](https://github.com/m33ch)


# Notes

- Do not append the word `service`, it will be automatically added for you.
