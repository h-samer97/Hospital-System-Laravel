<?php

namespace App\Repositories;

use App\Interfaces\IPatient;

class PatientRepository implements IPatient
{
    public function index()
    {
        $patients = \App\Models\Patient::latest()->get();
        return inertia('patients/SimplePatientList', ['patients' => $patients]);
    }

    public function create()
    {
        return inertia('patients/PatientForm');
    }

    public function store($request)
    {
        session()->flash('add');

        return redirect()->route('patients.index');
    }

    public function edit($id)
    {
        return inertia('patients/PatientForm', ['id' => $id]);
    }

    public function update($request)
    {
        session()->flash('edit');

        return redirect()->route('patients.index');
    }

    public function destroy($request)
    {
        session()->flash('delete');

        return redirect()->route('patients.index');
    }
}
