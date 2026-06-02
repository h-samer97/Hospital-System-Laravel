<?php

namespace App\Repositories;

use App\Http\Requests\StoreInsuranceRequest;
use App\Http\Requests\UpdateInsuranceRequest;
use App\Interfaces\IInsurance;
use App\Models\Insurance;
use Exception;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Override;

class InsuranceRepository implements IInsurance
{

    #[Override]
    public function index(): Response
    {

        $insurances = Insurance::query()
            ->select(
                'id',
                'name',
                'note',
                'insurance_code',
                'discount_percentage',
                'company_rate',
                'is_active',
                'created_at'
            )
            ->latest()
            ->get()
            ->map(fn(Insurance $i) => [
                'id'                  => $i->id,
                'name'                => $i->name,
                'note'               => $i->note,
                'insurance_code'      => $i->insurance_code,
                'discount_percentage' => $i->discount_percentage,
                'company_rate'        => $i->company_rate,
                'is_active'           => $i->is_active,
                'created_at'          => $i->created_at,
                'urls' => [
                    'update'  => route('insurances.update',  $i->id),
                    'destroy' => route('insurances.destroy', $i->id),
                ],
            ]);

        return Inertia::render('Insurances/Index', [
            'insurances' => $insurances,
            'url_store' => route('insurances.store')
        ]);
    }

    #[Override]
    public function store(StoreInsuranceRequest $request): RedirectResponse
    {
        try {
            $newInsurance = Insurance::create(
                $request->validated()
            );

            return \redirect()->route('insurances.index')->with('flash', [
                'type'    => 'success',
                'message' => 'Insurance added successfully',
            ]);

            # Flash Masseges has been saving in session[]

        } catch (Exception $error) {
            return \redirect()->route('insurances.index')->with('flash', [
                'type'    => 'errors',
                'message' => $error->getMessage(),
            ]);
        }
    }

    #[Override]
    public function update(UpdateInsuranceRequest $request, Insurance $insurance): RedirectResponse
    {
        try {
            $insurance->update($request->validated());
            return \redirect()->route('insurances.index')->with('flash', [
                'type'    => 'success',
                'message' => 'Insurance Updated successfully',
            ]);
        } catch (Exception $error) {
            return \redirect()->route('insurances.index')->with('flash', [
                'type'    => 'errors',
                'message' => $error->getMessage(),
            ]);
        }
    }

    #[Override]
    public function destroy(Insurance $insurance): RedirectResponse
    {
        try {

            $insurance->deleteOrFail();

            return \redirect()->route('insurances.index')->with('flash', [
                'type'    => 'success',
                'message' => 'Insurance Updated successfully',
            ]);
        } catch (Exception $error) {

            return \redirect()->route('insurances.index')->with('flash', [

                'type'    => 'errors',
                'message' => $error->getMessage(),

            ]);
        }
    }
}
