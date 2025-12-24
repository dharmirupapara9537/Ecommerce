<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
 
    public function boot(): void
    {
      //  Paginator::useBootstrapFive();   //  Bootstrap 5 pagination style
      Paginator::useBootstrapFour(); 
        // Or: Paginator::useBootstrapFour(); if using Bootstrap 4
    }
}
