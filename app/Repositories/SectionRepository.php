<?php

namespace App\Repositories;

use App\Interfaces\ISections;
use App\Models\Section;

/**
 * The TypeError occurred because the SectionController is type-hinting 'App\Http\Interfaces\ISections'
 * while this Repository implements 'App\Interfaces\ISections'. Ensure that the Interface file
 * namespace and the Controller import both match 'App\Interfaces\ISections'.
 */

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
        //
    }

    public function update($request)
    {
        $section = Section::findOrFail($request->id);
        $section->update([
            'name' => $request->input('name')
        ]);
        return redirect()->route('sections.index')->with('success', 'Section updated successfully');
    }

    public function destroy($id)
    {
        $section = Section::findOrFail($id);
        $section->delete();
        return redirect()->route('sections.index')->with('success', 'Section deleted successfully');
    }
}