<?php

namespace App\Repositories;

use App\Interfaces\IDoctor;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Section;
use App\Traits\UploadImage;
use Illuminate\Support\Facades\DB;

class DoctorRepository implements IDoctor
{
    use UploadImage;

    public function index()
    {
        $doctors = Doctor::with('section')->get();

        return view('doctors.index', compact('doctors'));
    }

    public function create()
    {
        $sections = Section::all();
        $appointments = Appointment::all();

        return view('doctors.add', compact('sections', 'appointments'));
    }

    public function store($request)
    {
        $data = $request->validated();
        $data['password'] = bcrypt($request->password);
        $data['appointments'] = implode(',', $request->appointments);
        $data['status'] = 1;

        try {
            DB::beginTransaction();

            $doctor = Doctor::create($data);

            if ($request->hasFile('photo')) {
                $this->uploadImage($request, 'photo', 'doctors', 'images', $doctor->id, Doctor::class);
            }

            DB::commit();

            return redirect()->route('doctors.index')->with('success', 'Doctor created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function update($request, $id)
    {
        $doctor = Doctor::findOrFail($id);
        $data = $request->validated();

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        } else {
            unset($data['password']);
        }

        $data['appointments'] = implode(',', $request->appointments);

        try {
            DB::beginTransaction();

            if ($request->hasFile('photo')) {
                // Delete old image if exists
                $this->deleteImage($doctor->id, Doctor::class, 'doctors', 'images');
                // Upload new image
                $this->uploadImage($request, 'photo', 'doctors', 'images', $doctor->id, Doctor::class);
            }

            $doctor->update($data);

            DB::commit();

            return redirect()->route('doctors.index')->with('success', 'Doctor updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy($request)
    {
        try {
            $doctorsId = explode(',', $request->delete_select_id);

            foreach ($doctorsId as $id) {
                $doctor = Doctor::findOrFail($id);
                $this->deleteImage($doctor->id, Doctor::class, 'doctors', 'images');
                $doctor->delete();
            }

            return redirect()->route('doctors.index')->with('success', 'Doctor deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('doctors.index')->with('error', 'Failed to delete doctor.');
        }
    }

    public function update_password($request)
    {
        $doctor = Doctor::findOrfail($request->id);
        $doctor->password = bcrypt($request->password);
        $doctor->save();
        flash()->success('Password updated successfully.');

        return redirect()->route('doctors.index')->with('success', 'Password updated successfully.');
    }

    public function update_status($id)
    {
        $doctor = Doctor::findOrFail($id);
        $doctor->status = ! $doctor->status;
        $doctor->save();
        flash()->success('Status updated successfully.');

        return redirect()->route('doctors.index')->with('success', 'Status updated successfully.');
    }

    public function show($id)
    {
        $doctors = Section::findOrFail($id)->doctors;
        $section = Section::findOrFail($id);

        return view('dashboard.Sections.show_doctors', compact('doctors', 'section'));
    }

    public function edit($id)
    {
        $doctor = Doctor::findOrFail($id);
        $sections = Section::all();

        return view('doctors.edit', compact('doctor', 'sections'));
    }
}
