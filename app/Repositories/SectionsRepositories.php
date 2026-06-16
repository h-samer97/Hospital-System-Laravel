<?php

namespace App\Repositories;

use App\Interfaces\ISections;
use App\Models\Section;
use App\Models\Doctor;
use Inertia\Inertia;
use App\Http\Requests\Dashboard\StoreSectionRequest;
use App\Http\Requests\Dashboard\UpdateSectionRequest;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class SectionsRepositories implements ISections
{

    public function index(): Response
    {
        $sections = Section::query()
            ->select('id', 'name', 'is_active') # SELECT [...]
            ->latest()                          # ORDER BY created_at DESC
            ->get();                            # GET COLLECTIONS

        return Inertia::render('Sections/Sections', [
            'sections' => $sections
        ]);
    }


    public function show(Section $section): RedirectResponse
    {

        $section->doctor()
            ->with(['image', 'appointments:id,name'])
            ->select('id', 'section_id', 'name', 'email', 'phone', 'is_active', 'created_at')
            ->get()
            ->map(fn(Doctor $d) => [
                'id'           => $d->id,
                'name'         => $d->name,
                'email'        => $d->email,
                'phone'        => $d->phone,
                'is_active'    => $d->is_active,
                'created_at'   => $d->created_at,
                'appointments' => $d->appointments->pluck('name')->join(', '),
                'image_url'    => $this->imageService->url($d),
                // URLs للعمليات
                'update_password_url' => route('doctors.updatePassword', $d->id),
                'update_status_url'   => route('doctors.updateStatus', $d->id),
                'update_url'          => route('doctors.update', $d->id),
                'delete_url'          => route('doctors.destroy', $d->id),
            ]);

        return Inertia::render('Sections/ShowDoctors', [
            'section' => $section->only('id', 'name'),
            'doctors' => $doctors,
        ]);
    }


    public function store(StoreSectionRequest $request): RedirectResponse
    {
        $sections = Section::create($request->validated());
        return redirect()->route('sections.index')->with('flash', [
            'type'    => 'success',
            'message' => 'تم إضافة القسم بنجاح',
        ]);
    }

    public function edit($id)
    {
        $section = Section::findOrFail($id);
        return Inertia::render('Sections/Edit', [
            'section' => $section
        ]);
    }

    public function update(UpdateSectionRequest $request, Section $section): RedirectResponse
    {
        $section->update($request->validated());
        return redirect()->route('sections.index')->with('flash', [
            'type'    => 'success',
            'message' => 'تم تحديث القسم بنجاح',
        ]);
    }

    public function destroy(Section $section): RedirectResponse
    {
        $section->delete();
        return redirect()->route('sections.index')->with('flash', [
            'type'    => 'success',
            'message' => 'تم حذف القسم بنجاح',
        ]);
    }

    // public function toggleActive($id) {
    //     $section = Section::findOrFail($id);
    //     $section->is_active = !$section->is_active;
    //     $section->save();
    //     return redirect()->route('sections.index')->with('flash', [
    //         'type'    => 'success',
    //         'message' => 'تم تغيير حالة القسم بنجاح',
    //     ]);
    // }

}
