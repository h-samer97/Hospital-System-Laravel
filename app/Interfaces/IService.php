<?php

namespace App\Interfaces;

use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;

interface IService
{
    public function index(): Response;

    public function store(StoreServiceRequest $request): RedirectResponse;

    public function update(UpdateServiceRequest $request, Service $service): RedirectResponse;

    public function destroy(Service $service): RedirectResponse;
}