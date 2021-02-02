<?php

namespace Arcphysx\Laradrive\Providers;

use Arcphysx\Laradrive\Commands\InstallCommand;
use Arcphysx\Laradrive\Laradrive;
use Google\Client;
use Google_Service_Drive;
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Application as LaravelApplication;
use Laravel\Lumen\Application as LumenApplication;

class LaradriveServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([__DIR__.'/../../config/laradrive.php' => config_path('laradrive.php')], 'config');
        }elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('laradrive');
        }

        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
            ]);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/laradrive.php', 'laradrive');
    }
}