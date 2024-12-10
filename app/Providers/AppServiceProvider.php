<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * This method is used to register bindings in the service container.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * This method is used to run code that should be executed during the bootstrapping of your application.
     */
    public function boot(): void
    {
        // Set the default string length for schema migrations to prevent key length issues
        Schema::defaultStringLength(191);
    }
}
