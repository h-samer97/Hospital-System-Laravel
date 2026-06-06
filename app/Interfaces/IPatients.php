<?php

namespace App\Interfaces;

use App\Http\Requests\StorePatientsRequest;
use App\Http\Requests\UpdatePatientsRequest;
use App\Models\Patients;
use Illuminate\Http\RedirectResponse;
use Inertia\Response as InertiaResponse;

interface IPatients
{
  public function index(): InertiaResponse;
  public function store(StorePatientsRequest $request): RedirectResponse;
  public function update(UpdatePatientsRequest $request, Patients $patient): RedirectResponse;
  public function destroy(Patients $patient): RedirectResponse;
  public function show(Patients $patient): InertiaResponse;
}
