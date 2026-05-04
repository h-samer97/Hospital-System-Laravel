<?php

namespace App\Repositories;

use App\Interfaces\IPatient;

class PatientRepository implements IPatient
{
    public function index()
    {
        return view('patients.index');
    }

    public function create()
    {
        return view('patients.create');
    }

    public function store($request)
    {
        session()->flash('add');

        return redirect()->route('patients.index');
    }

    public function edit($id)
    {
        return view('patients.edit');
    }

    public function update($request)
    {
        session()->flash('edit');

        return redirect()->route('patients.index');
    }

    public function destroy($request)
    {
        session()->flash('delete');

        return redirect()->route('patients.index');
    }
}
