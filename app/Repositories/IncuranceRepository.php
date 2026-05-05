<?php

namespace App\Repositories;

use App\Models\Incurance;
use App\Repositories\Interfaces\IIncurance;

class IncuranceRepository implements IIncurance
{
    public function index()
    {
        $incurance = Incurance::all();

        return inertia('incurance/InsuranceList', compact('incurance'));
    }

    public function create()
    {
        return inertia('incurance/InsuranceForm');
    }

    public function store($request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'description' => 'required',
            ]);

            Incurance::create($request->all());
            session()->flash('add');

            return redirect()->route('incurance.index')->with('success', 'Incurance created successfully.');

        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while creating the incurance: '.$e->getMessage());

            return redirect()->route('incurance.index')->with('error', 'An error occurred while creating the incurance: '.$e->getMessage());
        }
    }

    public function edit($id)
    {
        $incurance = Incurance::findOrFail($id);

        return inertia('incurance/InsuranceForm', compact('incurance'));
    }

    public function update($request)
    {
        $incurance = Incurance::findOrFail($request->id);
        $incurance->update($request->all());
        session()->flash('edit');

        return redirect()->route('incurance.index')->with('success', 'Incurance updated successfully.');
    }

    public function destroy($request)
    {
        try {
            $incurance = Incurance::findOrFail($request->id);
            $incurance->delete();
            session()->flash('delete');

            return redirect()->route('incurance.index')->with('success', 'Incurance deleted successfully.');

        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while deleting the incurance: '.$e->getMessage());

            return redirect()->route('incurance.index')->with('error', 'An error occurred while deleting the incurance: '.$e->getMessage());
        }
    }
}
