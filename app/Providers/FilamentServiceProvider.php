<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Illuminate\Support\ServiceProvider;

class FilamentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Pages are automatically discovered by AdminPanelProvider
        // No need to register them manually here
        
        Filament::serving(function () {
            // Add any global Filament configurations here if needed
        });
    }
}
