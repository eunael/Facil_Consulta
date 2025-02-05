<?php

namespace App\Providers;

use Illuminate\Support\Str;
use Dedoc\Scramble\Scramble;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Tymon\JWTAuth\JWTGuard;

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
        Auth::extend('jwt', function(Application $app, string $name, array $config) {
            return new JwtGuard(
                $app['tymon.jwt'],
                $app['auth']->createUserProvider($config['provider']),
                $app['request']
            );
        });

        Scramble::routes(function (Route $route) {
            return Str::startsWith($route->uri, 'api/');
        });
    }
}
