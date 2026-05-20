<?php

namespace App\Providers;

use App\Interfaces\IDoctor;
use App\Interfaces\IService;
use App\Repositories\DoctorRepository;
use App\Repositories\ServiceRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(IDoctor::class, DoctorRepository::class);
        $this->app->bind(IService::class, ServiceRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
