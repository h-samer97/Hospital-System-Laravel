<?php

namespace App\Repositories;

use App\Interfaces\ISections;
use App\Models\Section;

class SectionRepository implements ISections
{
    public function index()
    {
        $sections = Section::all();
        return view('sections.index', compact('sections'));
    }

    public function create()
    {
        return view('sections.add');
    }

    public function store($request)
    {
        Section::create([
            'name' => $request->input('name')
        ]);

        session()->flash('success', 'Section created successfully');
        return redirect()->route('sections.index');
    }


    public function edit($id)
    {
        $sections = Section::findOrFail($id);
        return view('sections.edit', compact('sections'));
    }

    public function update($request)
    {
        $section = Section::findOrFail($request->id);
        $section->update([
            'name' => $request->input('name')
        ]);
        return redirect()->route('sections.index')->with('success', 'Section updated successfully');
    }

    public function destroy($request)
    {
        $section = Section::findOrFail($request->id);
        $section->delete();
        return redirect()->route('sections.index')->with('success', 'Section deleted successfully');
    }
}