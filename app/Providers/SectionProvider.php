<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\ISections;
use App\Repositories\SectionRepository;

class SectionProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            ISections::class,
            SectionRepository::class
        );
    }

    public function boot(): void
    {
        //
    }
}
