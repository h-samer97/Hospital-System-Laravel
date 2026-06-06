<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePatientsRequest;
use App\Http\Requests\UpdatePatientsRequest;
use App\Interfaces\IPatients;
use App\Models\Patients;
use Illuminate\Http\Request;

class PatientsController extends Controller
{
    public function __construct(private readonly IPatients $patientsRepo) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->patientsRepo->index();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePatientsRequest $request)
    {
        return $this->patientsRepo->store($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(Patients $patient)
    {
        return $this->patientsRepo->show($patient);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePatientsRequest $request, Patients $patient)
    {
        return $this->patientsRepo->update($request, $patient);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patients $patient)
    {
        return $this->patientsRepo->destroy($patient);
    }
}
