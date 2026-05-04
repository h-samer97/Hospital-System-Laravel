<?php

namespace App\Providers;

use App\Interfaces\IInsurance;
use App\Repository\InsuranceRepository;
use Illuminate\Support\ServiceProvider;

class InsuranceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            IInsurance::class,
            InsuranceRepository::class
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
