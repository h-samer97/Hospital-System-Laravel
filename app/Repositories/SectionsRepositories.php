<?php

namespace App\Repositories;

use App\Interfaces\ISections;
use App\Models\Section;
use Inertia\Inertia;
use App\Http\Requests\dashboard\StoreSectionRequest;
use App\Http\Requests\dashboard\UpdateSectionRequest;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class SectionsRepositories implements ISections  {

    public function index(): Response {
        $sections = Section::query()
        ->select('id', 'name', 'is_active') # SELECT [...]
        ->latest()                          # ORDER BY created_at DESC
        ->get();                            # GET COLLECTIONS

        return Inertia::render('Sections/Sections', [
            'sections' => $sections
        ]);
    }

    public function store(StoreSectionRequest $request): RedirectResponse {
        $sections = Section::create($request->validated());
        return redirect()->route('sections.index')->with('flash', [
                'type'    => 'success',
                'message' => 'تم إضافة القسم بنجاح',
            ]);
    }

    public function edit($id) {
        $section = Section::findOrFail($id);
        return Inertia::render('Sections/Edit', [
            'section' => $section
        ]);
    }
    
    public function update(UpdateSectionRequest $request, Section $section): RedirectResponse {
        $section->update($request->validated());
        return redirect()->route('sections.index')->with('flash', [
            'type'    => 'success',
            'message' => 'تم تحديث القسم بنجاح',
        ]);
    }
    
    public function destroy(Section $section): RedirectResponse {
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

