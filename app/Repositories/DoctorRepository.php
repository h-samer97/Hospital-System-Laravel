<?php

namespace App\Repositories;

use App\Interfaces\IDoctor;
use App\Models\Doctor;
use App\Models\Section;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;
use App\Http\Requests\Dashboard\StoreDoctorRequest;
use App\Http\Requests\Dashboard\UpdateDoctorRequest;
use App\Services\ImageUploadService;


class DoctorRepository implements IDoctor
{

    use ImageUploadService;

    public function index() : Response
    {
       $doctors = Doctor::with(['section:id,name', 'image'])
       ->select('id','section_id','name','email','phone','price','is_active','created_at', 'appointments')
       ->latest() # DASC
       ->get()
       ->map(function ($doctor) { # ===> Draw data Map ^_*
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
               'image' => $this->getUrl($doctor, 'doctors'),
            //    'image_url' => $doctor->image ? $doctor->image->url : null,
               'edit_url' => route('doctors.edit', $doctor->id),
               'delete_url' => route('doctors.destroy', $doctor->id),
               'store_url' => route('doctors.store'),
           ];
       });

       $sections = Section::where('is_active', true)
       ->select('id', 'name')
       ->get();

       return inertia('Doctors/Index', [
           'doctors' => $doctors,
           'sections' => $sections,
       ]);
       
    }

    public function store(StoreDoctorRequest $request) : Response
    {

        $data = $request->validated();
        $doctor = Doctor::create($data);

        if($request->hasFile('image')) {

           $this->Upload(Doctor::class, $request->file('image'), 'doctors');
        }

        return response()->json([
            'message' => 'Doctor created successfully',
        ]);
        
    }

    public function update(UpdateDoctorRequest $request) : Response
    {
        $doctor = Doctor::findOrFail($request->id);
        $data = $request->validated();

        if(!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }


        $doctor->update($data);

        if($request->hasFile('image')) {
            $this->Upload(Doctor::class, $request->file('image'), 'doctors');
        }

        return response()->json([
            'message' => 'Doctor updated successfully',
        ]);
        

    }

    public function destroy(Doctor $doctor) : Response
    {
        $doctor->delete();
        $this->Delete(Doctor::class, 'doctors');
        return response()->json([
            'message' => 'Doctor deleted successfully',
        ]);
    }
}