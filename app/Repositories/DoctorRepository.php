<?php

namespace App\Repositories;

use App\Interfaces\IDoctor;
use App\Models\Doctor;
use App\Models\Section;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;


class DoctorRepository implements IDoctor
{
    public function index() : Response
    {
       $doctors = Doctor::with(['section:id,name', 'image'])
       ->select('id','section_id','name','email','phone','price','is_active','created_at', 'appointments')
       ->latest() # DASC
       ->get()
       ->map(function ($doctor) {
           return [
               'id' => $doctor->id,
               'section_id' => $doctor->section_id,
               'name' => $doctor->name,
               'email' => $doctor->email,
               'phone' => $doctor->phone,
               'price' => $doctor->price,
               'is_active' => $doctor->is_active,
               'created_at' => $doctor->created_at,
               'appointments' => $doctor->appointments,
               'section' => $doctor->section,
               'image' => $doctor->image,
               'image_url' => $doctor->image ? $doctor->image->url : null,
               'edit_url' => route('doctors.edit', $doctor->id),
               'delete_url' => route('doctors.destroy', $doctor->id),
               'store_url' => route('doctors.store'),
           ];
       });

       $sections = Section::where('is_active', true)
       ->select('id', 'name')
       ->get();

        dd($doctors, $sections);

    //    return inertia('Dashboard/Doctors/Index', [
    //        'doctors' => $doctors,
    //        'sections' => $sections,
    //    ]);
       
      
    }

    public function store(\App\Http\Requests\Dashboard\StoreDoctorRequest $request) : Response
    {
        // TODO: Implement store() method.
    }

    public function update(\App\Http\Requests\Dashboard\UpdateDoctorRequest $request, \App\Models\Doctor $doctor) : Response
    {
        // TODO: Implement update() method.
    }

    public function destroy(\App\Models\Doctor $doctor) : Response
    {
        // TODO: Implement destroy() method.
    }
}