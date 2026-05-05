<?php

namespace App\Repositories;

use App\Interfaces\IAmbulance;
use App\Models\Ambulance;
use App\Models\Incurance;

class AmbulanceRepository implements IAmbulance
{
    public function index()
    {
        return Ambulance::all();
    }

    public function create()
    {
        $incurances = Incurance::all();

        return inertia('ambulances/AmbulanceForm', compact('incurances'));
    }

    public function store($request)
    {
        Ambulance::create([
            'name' => $request->name,
            'number' => $request->number,
            'phone' => $request->phone,
            'incurance_id' => $request->incurance_id,
        ]);
        session()->flash('add');

        return redirect()->route('ambulances.index');
    }

    public function edit($id)
    {
        $ambulance = Ambulance::findOrFail($id);
        $incurances = Incurance::all();

        return inertia('ambulances/AmbulanceForm', compact('ambulance', 'incurances'));
    }

    public function update($request)
    {
        $ambulance = Ambulance::findOrFail($request->id);
        $ambulance->update([
            'name' => $request->name,
            'number' => $request->number,
            'phone' => $request->phone,
            'incurance_id' => $request->incurance_id,
        ]);
        session()->flash('edit');

        return redirect()->route('ambulances.index');
    }

    public function destroy($request)
    {
        Ambulance::destroy($request->id);
        session()->flash('delete');

        return redirect()->route('ambulances.index');
    }
}
