<?php

namespace App\Providers;

use App\Interfaces\IDoctor;
use App\Interfaces\IGroups;
use App\Interfaces\IService;
use App\Repositories\DoctorRepository;
use App\Repositories\GroupsRepository;
use App\Repositories\ServiceRepository;
use Illuminate\Support\ServiceProvider;
use App\Interfaces\ISections;
use App\Repositories\SectionsRepositories;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(IDoctor::class, DoctorRepository::class);
        $this->app->bind(IService::class, ServiceRepository::class);
        $this->app->bind(IGroups::class, GroupsRepository::class);
        $this->app->bind(ISections::class, SectionsRepositories::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
