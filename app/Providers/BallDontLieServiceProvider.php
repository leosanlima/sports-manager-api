<?php

namespace App\Providers;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class BallDontLieServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('balldontlie', function ($app) {
            return new \App\Services\BallDontLieService(new Client([
                'base_uri' => config('balldontlie.url'),
                'timeout'  => 5.0,
                'headers'  => [
                    'Authorization' => config('balldontlie.api_key'),
                ],
            ]));
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
