<?php

namespace App\Interfaces;

use App\Http\Requests\Dashboard\StoreDoctorRequest;
use App\Http\Requests\Dashboard\UpdateDoctorRequest;
use App\Models\Doctor;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;
use App\Http\Requests\UpdateDoctorPasswordRequest;
use App\Http\Requests\UpdateDoctorStatusRequest;

interface IDoctor
{
    public function index(): Response;
    public function create(): Response;
    public function edit(Doctor $doctor): Response;
    public function store(StoreDoctorRequest $request): RedirectResponse;
    public function update(UpdateDoctorRequest $request): RedirectResponse;
    public function destroy(Doctor $doctor) : RedirectResponse;
    public function destroyBulk(array $ids) : RedirectResponse;
    public function updatePassword(UpdateDoctorPasswordRequest $request, Doctor $doctor): RedirectResponse;
    public function updateStatus(UpdateDoctorStatusRequest $request, Doctor $doctor): RedirectResponse;
}
