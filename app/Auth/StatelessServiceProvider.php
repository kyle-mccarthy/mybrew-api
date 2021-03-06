<?php namespace App\Auth;

use Illuminate\Support\ServiceProvider;

class StatelessServiceProvider extends ServiceProvider
{
    /**
     * Register the api auth singleton with the application.
     */
    public function register()
    {
        $this->app->singleton('ApiGuard', function($app) {
            return new StatelessGuard($app['request']);
        });
    }
}