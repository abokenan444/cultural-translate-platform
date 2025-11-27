<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Guards\TokenGuard;

class TokenGuardServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Auth::extend('token', function ($app, $name, array $config) {
            return new TokenGuard(
                Auth::createUserProvider($config['provider']),
                $app->make('request')
            );
        });
    }
}
