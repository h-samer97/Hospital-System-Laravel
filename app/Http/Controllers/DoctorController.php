<?php

namespace App\Http\Controllers;

use App\Interfaces\IDoctor;
use App\Http\Requests\Dashboard\StoreDoctorRequest;
use App\Http\Requests\Dashboard\UpdateDoctorRequest;
use App\Http\Requests\UpdateDoctorPasswordRequest;
use App\Http\Requests\UpdateDoctorStatusRequest;
use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    

    public function __construct(private readonly IDoctor $doctorRepo )
    {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->doctorRepo->index();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return $this->doctorRepo->create();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Doctor $doctor)
    {
        return $this->doctorRepo->edit($doctor);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDoctorRequest $request)
    {
        return $this->doctorRepo->store($request);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDoctorRequest $request)
    {
        return $this->doctorRepo->update($request);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Doctor $doctor)
    {
        return $this->doctorRepo->destroy($doctor);
    }

    /**
     * Remove multiple resources from storage.
     */
    public function destroyBulk(Request $request)
    {
        return $this->doctorRepo->destroyBulk($request->ids);
    }

    public function updatePassword(UpdateDoctorPasswordRequest $request, Doctor $doctor)
    {
        return $this->doctorRepo->updatePassword($request, $doctor);
    }

    public function updateStatus(UpdateDoctorStatusRequest $request, Doctor $doctor)
    {
        return $this->doctorRepo->updateStatus($request, $doctor);
    }
}
