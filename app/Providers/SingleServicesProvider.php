<?php

namespace App\Providers;

use App\Interfaces\ISingleService;
use App\Repositories\SingleServiceRepository;
use Illuminate\Support\ServiceProvider;

class SingleServicesProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            ISingleService::class,
            SingleServiceRepository::class
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
