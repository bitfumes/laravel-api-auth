<?php

namespace Bitfumes\ApiAuth;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ApiAuthServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/ApiAuth.php', 'api-auth');
        $this->publishThings();
        $this->loadViewsFrom(__DIR__ . '/Views', 'api-auth');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->registerRoutes();
    }

    /**
     * Register the package routes.
     *
     * @return void
     */
    private function registerRoutes()
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__ . '/Http/routes.php');
        });
    }

    /**
    * Get the Blogg route group configuration array.
    *
    * @return array
    */
    private function routeConfiguration()
    {
        return [
            'namespace'  => "Bitfumes\ApiAuth\Http\Controllers",
            'middleware' => 'api',
            'prefix'     => 'api',
        ];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Register facade
        $this->app->singleton('api-auth', function () {
            return new ApiAuth;
        });
    }

    public function publishThings()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/ApiAuth.php' => config_path('ApiAuth.php'),
            ], 'config');
        }
    }
}
