<?php

use App\Providers\AmbulanceServiceProvider;
use App\Providers\AmbulancesServiceProvider;
use App\Providers\AppServiceProvider;
use App\Providers\DoctorProvider;
use App\Providers\InsuranceServiceProvider;
use App\Providers\PatientServiceProvider;
use App\Providers\SectionProvider;
use App\Providers\SingleServicesProvider;

return [
    AmbulanceServiceProvider::class,
    AmbulancesServiceProvider::class,
    AppServiceProvider::class,
    DoctorProvider::class,
    InsuranceServiceProvider::class,
    PatientServiceProvider::class,
    SectionProvider::class,
    SingleServicesProvider::class,

];
