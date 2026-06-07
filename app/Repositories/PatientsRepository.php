<?php

namespace App\Repositories;

use App\Interfaces\IPatients;
use App\Http\Requests\StorePatientsRequest;
use App\Http\Requests\UpdatePatientsRequest;
use App\Models\Patients;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\RedirectResponse;
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
        'gender_label' => $patient->gender === 'male' ? 'Male' : 'Female',
        'age' => $patient->birth_date ? Carbon::parse($patient->birth_date)->age : null,
        'blood_group' => $patient->blood_group,
        'address' => $patient->address,
        'is_active' => true,
        'created_at' => $patient->created_at,
        'updated_at' => $patient->updated_at,
        'urls' => [
          'update' => route('patients.update', $patient->id),
          'destroy' => route('patients.destroy', $patient->id),
        ],
      ]);

    return Inertia::render('Patients/Index', [
      'patients' => $patients,
      'url_store' => route('patients.store'),
    ]);
  }

  public function store(StorePatientsRequest $request): RedirectResponse
  {
    try {
      Patients::create($request->validated());

      return redirect()->route('patients.index')->with('flash', [
        'type' => 'success',
        'message' => 'Patient added successfully',
      ]);
    } catch (Exception $error) {
      return redirect()->route('patients.index')->with('flash', [
        'type' => 'error',
        'message' => 'Failed to add patient: ' . $error->getMessage(),
      ]);
    }
  }

  public function update(UpdatePatientsRequest $request, Patients $patient): RedirectResponse
  {
    try {
      $patient->update($request->validated());

      return redirect()->route('patients.index')->with('flash', [
        'type' => 'success',
        'message' => 'Patient updated successfully',
      ]);
    } catch (Exception $error) {
      return redirect()->route('patients.index')->with('flash', [
        'type' => 'error',
        'message' => 'Failed to update patient: ' . $error->getMessage(),
      ]);
    }
  }

  public function destroy(Patients $patient): RedirectResponse
  {
    try {
      $patient->delete();

      return redirect()->route('patients.index')->with('flash', [
        'type' => 'success',
        'message' => 'Patient deleted successfully',
      ]);
    } catch (Exception $error) {
      return redirect()->route('patients.index')->with('flash', [
        'type' => 'error',
        'message' => 'Failed to delete patient: ' . $error->getMessage(),
      ]);
    }
  }

  public function show(Patients $patient): Response
  {
    return Inertia::render('Patients/Show', [
      'patient' => [
        'id' => $patient->id,
        'name' => $patient->name,
        'email' => $patient->email,
        'password' => $patient->password,
        'birth_date' => $patient->birth_date,
        'phone' => $patient->phone,
      ],
    ]);
  }
}
