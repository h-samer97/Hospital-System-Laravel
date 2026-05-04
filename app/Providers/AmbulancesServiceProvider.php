<?php

namespace App\Providers;

use App\Interfaces\IAmbulance;
use App\Repository\AmbulanceRepository;
use Illuminate\Support\ServiceProvider;

class AmbulancesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            IAmbulance::class,
            AmbulanceRepository::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
