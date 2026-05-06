<?php

namespace App\Http\Controllers;

use App\Interfaces\IPatient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    protected IPatient $patient;

    public function __construct(IPatient $patient)
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

    public function edit($id)
    {
        return $this->patient->edit($id);
    }

    public function update(Request $request)
    {
        return $this->patient->update($request);
    }

    public function destroy($id)
    {
        return $this->patient->destroy($id);
    }
}
