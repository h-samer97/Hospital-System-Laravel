<?php

namespace App\Providers;

use App\Interfaces\IPatient;
use App\Repositories\PatientRepository;
use Illuminate\Support\ServiceProvider;

class PatientServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            IPatient::class,
            PatientRepository::class
        );
    }

    public function boot(): void
    {
        //
    }
}
