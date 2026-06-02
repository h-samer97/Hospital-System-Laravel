<?php


namespace App\Interfaces;

use App\Http\Requests\StoreInsuranceRequest;
use App\Http\Requests\UpdateInsuranceRequest;
use App\Models\Insurance;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;


interface IInsurance
{

    public function index(): Response;
    public function store(StoreInsuranceRequest $request): RedirectResponse;
    public function update(UpdateInsuranceRequest $request, Insurance $insurance): RedirectResponse;
    public function destroy(Insurance $insurance): RedirectResponse;
}
