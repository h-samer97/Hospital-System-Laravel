<?php

use App\Providers\AppServiceProvider;
use App\Providers\DoctorProvider;
use App\Providers\SectionProvider;
use App\Providers\SingleServicesProvider;

return [
    AppServiceProvider::class,
    DoctorProvider::class,
    SectionProvider::class,
    SingleServicesProvider::class,
];
