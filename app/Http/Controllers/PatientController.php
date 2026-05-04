<?php

namespace App\Http\Controllers;

use App\Interfaces\IPatient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    protected IPatient $patient;

    public function __constrauct(IPatient $patient)
    {
        $this->patient = $patient;
    }

    public function index()
    {
        return $this->patient->index();
    }

    public function create()
    {
        return $this->patient->create();
    }

    public function store(StorePatientRequest $request)
    {
        return $this->patient->store($request);
    }

    public function edit(StorePatientRequest $request)
    {
        return $this->patient->edit($request);
    }

    public function update(Request $request)
    {
        return $this->patient->update($request);
    }

    public function destroy(Request $request)
    {
        return $this->patient->destroy($request);
    }
}
