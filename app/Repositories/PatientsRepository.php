<?php


namespace App\Repositories;

use App\Interfaces\IPatients;
use App\Models\Patients;
use Exception;
use Inertia\Response as Response;
use Inertia\Inertia;

class PatientsRepository implements IPatients
{

  public function index(): Response
  {

    $patients = Patients::query()
      ->select('id', 'name', 'email', 'password', 'birth_date', 'phone', 'gender', 'blood_group', 'address', 'created_at', 'updated_at')
      ->latest()
      ->get()
      ->map(fn(Patients $patient) => [
        'id' => $patient->id,
        'name' => $patient->name,
        'email' => $patient->email,
        'password' => $patient->password,
        'birth_date' => $patient->birth_date,
        'phone' => $patient->phone,
        'gender' => $patient->gender,
        'blood_group' => $patient->blood_group,
        'address' => $patient->address,
        'created_at' => $patient->created_at,
        'updated_at' => $patient->updated_at,
        'urls' => [
          'update' => route('patients.update', $patient->id),
          'destroy' => route('patients.destroy', $patient->id),
        ],
      ]);

    return Inertia::render('Patients/Index', [
      'patients' => $patients,
      'url_store' => \route('patients.store'),
    ]);
  }

  public function store(StorePatientsRequest $request): RedirectResponse
  {
    try {

        Patients::create(
          $request->validated()
        );

        return \redirect()->route('patients.index')->with('flash', [
             'type'    => 'success',
              'message' => 'Insurance added successfully',
        ]);

    } catch(Exception $error) {



    }
  }

  public function update(UpdatePatientsRequest $request, Patients $patient): RedirectResponse {}

  public function destroy(Patients $patient): RedirectResponse {}

  public function show(Patients $patient): Response
  {
    // Implementation for showing a specific patient record
  }
}
