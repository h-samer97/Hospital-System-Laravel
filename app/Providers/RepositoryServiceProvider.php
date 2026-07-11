<?php

namespace App\Providers;

use App\Interfaces\IAmbulance;
use App\Interfaces\IDoctor;
use App\Interfaces\IGroups;
use App\Interfaces\IInsurance;
use App\Interfaces\IPatients;
use App\Interfaces\IPayment;
use App\Interfaces\IService;
use App\Interfaces\ISections;
use App\Interfaces\ISingleInvoice;
use App\Interfaces\IReceiptAccount;
use App\Repositories\AmbulancesRepository;
use App\Repositories\DoctorRepository;
use App\Repositories\GroupsRepository;
use App\Repositories\InsuranceRepository;
use App\Repositories\PatientsRepository;
use App\Repositories\PaymentAccountRepository;
use App\Repositories\SectionsRepositories;
use App\Repositories\ServiceRepository;
use App\Repositories\SingleInvoiceRepository;
use App\Repositories\ReceiptAccountRepository;
use App\Services\InvoiceService;
use App\Services\PaymentService;
use App\Services\ReceiptService;
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
        $this->app->bind(IGroups::class, GroupsRepository::class);
        $this->app->bind(ISections::class, SectionsRepositories::class);
        $this->app->bind(IInsurance::class, InsuranceRepository::class);
        $this->app->bind(IPatients::class, PatientsRepository::class);
        $this->app->bind(IAmbulance::class, AmbulancesRepository::class);
        $this->app->bind(IReceiptAccount::class, ReceiptAccountRepository::class);
        $this->app->singleton(InvoiceService::class);
        $this->app->singleton(ReceiptService::class);
        $this->app->singleton(PaymentService::class);
        $this->app->bind(IPayment::class, PaymentAccountRepository::class);

        $this->app->bind(ISingleInvoice::class, SingleInvoiceRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
