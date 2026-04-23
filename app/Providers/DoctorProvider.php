<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\IDoctor;
use App\Repositories\DoctorRepository;

class DoctorProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            IDoctor::class, DoctorRepository::class
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
