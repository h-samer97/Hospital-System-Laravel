<?php

namespace App\Interfaces;

use App\Http\Requests\Dashboard\StoreDoctorRequest;
use App\Http\Requests\Dashboard\UpdateDoctorRequest;
use App\Models\Doctor;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;

interface IDoctor
{
    public function index(): Response;
    public function store(StoreDoctorRequest $request): Response;
    public function update(UpdateDoctorRequest $request, Doctor $doctor): Response;
    public function destroy(Doctor $doctor): Response;
}