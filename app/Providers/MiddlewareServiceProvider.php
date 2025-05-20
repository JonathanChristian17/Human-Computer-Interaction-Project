<?php

namespace App\Providers;

use App\Http\Middleware\ReceptionistMiddleware;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\ServiceProvider;

class MiddlewareServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('receptionist', function ($app) {
            return new ReceptionistMiddleware();
        });

        $this->app->singleton('admin', function ($app) {
            return new AdminMiddleware();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
} 