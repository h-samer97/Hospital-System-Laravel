<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\DoctorStoreRequest;
use App\Http\Requests\DoctorUpdateRequest;
use App\Interfaces\IDoctor;
use App\Models\Doctor;
use App\Models\Section;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    private IDoctor $doctor;

    public function __construct(IDoctor $doctor)
    {

        $this->doctor = $doctor;

    }

    public function index()
    {
        return $this->doctor->index();
    }

    public function create()
    {
        return $this->doctor->create();
    }

    public function store(DoctorStoreRequest $request)
    {
        return $this->doctor->store($request);
    }

    public function show($id)
    {
        return $this->doctor->show($id);
    }

    public function edit($id)
    {
        $doctor = Doctor::findOrFail($id);
        $sections = Section::all();

        return view('doctors.edit', compact('doctor', 'sections'));
    }

    public function update(DoctorUpdateRequest $request, $id)
    {
        return $this->doctor->update($request, $id);
    }

    public function destroy(Request $request)
    {
        return $this->doctor->destroy($request);
    }

    public function update_password(Request $request)
    {
        return $this->doctor->update_password($request);
    }

    public function update_status($id)
    {
        return $this->doctor->update_status($id);
    }
}
