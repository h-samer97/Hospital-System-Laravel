<?php

namespace App\Repositories;

use App\Interfaces\IDoctor;
use App\Models\Doctor;
use App\Models\Section;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Inertia\Response;
use App\Http\Requests\Dashboard\StoreDoctorRequest;
use App\Http\Requests\Dashboard\UpdateDoctorRequest;
use App\Services\ImageUploadService;
use App\Http\Requests\UpdateDoctorPasswordRequest;
use App\Http\Requests\UpdateDoctorStatusRequest;

class DoctorRepository implements IDoctor
{

    use ImageUploadService;

    public function index(): Response
    {
        $doctors = Doctor::with(['section:id,name', 'image', 'appointments'])
            ->select('id', 'section_id', 'name', 'email', 'phone', 'price', 'is_active', 'created_at')
            ->latest() # DASC
            ->get()
            ->map(function ($doctor) { # ===> Draw data Map ^_*
                return [
                    'id' => $doctor->id,
                    'section_id' => $doctor->section_id,
                    'name' => $doctor->name,
                    'email' => $doctor->email,
                    'phone' => $doctor->phone,
                    'is_active' => $doctor->is_active,
                    'created_at' => $doctor->created_at,
                    'appointments' => $doctor->appointments,
                    'section' => $doctor->section,
                    'image' => $this->getUrl($doctor, 'doctors'),
                    'edit_url' => route('doctors.update', $doctor->id),
                    'delete_url' => route('doctors.destroy', $doctor->id),
                    'store_url' => route('doctors.store'),
                    'destroy_bulk_url' => route('doctors.destroyBulk'),
                ];
            });

        $sections = Section::where('is_active', true)
            ->select('id', 'name')
            ->get();

        $appointments = \App\Models\Appointment::select('id', 'name')->get();

        return inertia('Doctors/Index', [
            'doctors' => $doctors,
            'sections' => $sections,
            'appointments' => $appointments,
            'store_url' => route('doctors.store'),
        ]);
    }

    public function create(): Response
    {
        $sections = Section::where('is_active', true)
            ->select('id', 'name')
            ->get();

        $appointments = \App\Models\Appointment::select('id', 'name')->get();

        return inertia('Doctors/Create', [
            'sections' => $sections,
            'appointments' => $appointments,
            'store_url' => route('doctors.store'),
        ]);
    }

    public function edit(Doctor $doctor): Response
    {
        $doctor->load(['section:id,name', 'appointments:id,name']);

        $sections = Section::where('is_active', true)
            ->select('id', 'name')
            ->get();

        $appointments = \App\Models\Appointment::select('id', 'name')->get();

        return inertia('Doctors/Edit', [
            'doctor' => [
                'id' => $doctor->id,
                'section_id' => $doctor->section_id,
                'name' => $doctor->name,
                'email' => $doctor->email,
                'phone' => $doctor->phone,
                'price' => $doctor->price,
                'is_active' => $doctor->is_active,
                'image_url' => $this->getUrl($doctor, 'doctors'),
                'section' => $doctor->section,
                'appointments' => $doctor->appointments,
                'edit_url' => route('doctors.update', $doctor->id),
            ],
            'sections' => $sections,
            'appointments' => $appointments,
        ]);
    }

    public function store(StoreDoctorRequest $request): RedirectResponse
    {

        $data = $request->validated();
        $doctor = Doctor::create($data);

        $doctor->appointments()->attach($request->appointment_ids);

        if ($request->hasFile('image')) {

            $this->Upload($doctor, $request->file('image'), 'doctors');
        }

        return redirect()->route('doctors.index')->with('success', 'Doctor created successfully');
    }

    public function update(UpdateDoctorRequest $request): RedirectResponse
    {
        $doctor = Doctor::findOrFail($request->id);
        $data = $request->validated();

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }


        $doctor->update($data);

        $doctor->appointments()->sync($request->appointment_ids);

        if ($request->hasFile('image')) {
            $this->Upload($doctor, $request->file('image'), 'doctors');
        }

        return redirect()->route('doctors.index')->with('success', 'Doctor updated successfully');
    }

    public function destroy(Doctor $doctor): RedirectResponse
    {
        $doctor->appointments()->detach();
        $this->Delete($doctor, 'doctors');
        $doctor->delete();
        return redirect()->back()->with('success', 'Doctor deleted successfully');
    }

    public function destroyBulk(array $IDs): RedirectResponse
    {

        $doctors = Doctor::whereIn('id', $IDs)->with('image')->get();

        foreach ($doctors as $doctor) {

            $this->Delete($doctor); # Delete Image

            $doctor->appointments()->detach(); // فك ربط المواعيد

            $doctor->delete();
        }

        return redirect()->back()->with('success', 'Doctors deleted successfully');
    }


    public function updatePassword(UpdateDoctorPasswordRequest $request, Doctor $doctor): RedirectResponse
    {
        $doctor->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with('success', 'Password updated successfully');
    }

    public function updateStatus(UpdateDoctorStatusRequest $request, Doctor $doctor): RedirectResponse
    {
        $doctor->update([
            'is_active' => $request->is_active,
        ]);

        return redirect()->back()->with('success', 'Status updated successfully');
    }
}
