<?php

namespace Dipeshsukhia\LaravelApiDevelop;

use Dipeshsukhia\LaravelApiDevelop\Console\Commands\DevelopApi;
use Dipeshsukhia\LaravelApiDevelop\Console\Commands\ExportLaravelApiDevelopDefaults;
use Illuminate\Support\ServiceProvider;

class LaravelApiDevelopServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/resources/stubs/ApiController.stub' => app_path('Http/Controllers/Api/ApiController.php'),
                __DIR__ . '/resources/stubs/ApiHandlerTrait.stub' => app_path('Exceptions/Traits/ApiHandlerTrait.php'),
                __DIR__ . '/resources/stubs/ApiJson.stub' => app_path('Http/Resources/Macros/ApiJson.php'),
                __DIR__ . '/resources/stubs/ApiResourceTrait.stub' => app_path('Http/Resources/Traits/ApiResourceTrait.php'),
                __DIR__.'/../config/config.php' => config_path('laravel-api-develop.php'),
            ], 'LaravelApiDevelop');
            // Registering package commands.
            $this->commands([
                DevelopApi::class,
                ExportLaravelApiDevelopDefaults::class,
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'laravel-api-develop');

        // Register the main class to use with the facade
        $this->app->singleton('laravel-api-develop', function () {
            return new LaravelApiDevelop;
        });
    }
}
