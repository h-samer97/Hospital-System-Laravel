<?php

namespace App\Repositories;

use App\Interfaces\IDoctor;
use App\Models\Doctor;
use App\Models\Section;
use Illuminate\Support\Facades\DB;

class DoctorRepository implements IDoctor
{

    public function index()
    {
        $doctors = Doctor::all();
        return view('doctors.index', compact('doctors'));
    }

    public function create()
    {
        $sections = Section::all();
        return view('doctors.add', compact('sections'));
    }

    public function store($request)
    {

        $data = $request->validated();

        $data['password'] = bcrypt($request->password);
        $data['appointments'] = implode(",", $request->appointments);
        $data['status'] = 1;

        try {

            DB::beginTransaction();

            Doctor::create($data);

            DB::commit();

        return redirect()->route('doctors.index')->with('success', 'Doctor created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('doctors.index')->with('error', 'Failed to create doctor: ' . $e->getMessage());
        }
    }

    public function update($request, $id)
    {
       $doctor = Doctor::findOrFail($id);
       $data = $request->validated();
       if (isset($data['password'])) {
           $data['password'] = bcrypt($request->password);
       }
       $data['appointments'] = implode(",", $request->appointments);
       try {
           DB::beginTransaction();
           $doctor->update($data);
           DB::commit();
           return redirect()->route('doctors.index')->with('success', 'Doctor updated successfully.');
       } catch (\Exception $e) {
           DB::rollBack();
           return redirect()->route('doctors.index')->with('error', 'Failed to update doctor: ' . $e->getMessage());
       }
    }

    public function destroy($id)
    {
        $doctor = Doctor::findOrFail($id);
        try {
            DB::beginTransaction();
            $doctor->delete();
            DB::commit();
            return redirect()->route('doctors.index')->with('success', 'Doctor deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('doctors.index')->with('error', 'Failed to delete doctor: ' . $e->getMessage());
        }
    }
}