<?php

namespace App\Http\Controllers;

use App\Models\Ambulances;
use Illuminate\Http\Request;

class AmbulancesController extends Controller
{
    protected IAmbulance $ambulance;

    public function __construct(IAmbulance $ambulance)
    {
        $this->ambulance = $ambulance;
    }

    public function index()
    {
        return $this->ambulance->index();
    }

    public function create()
    {
        return $this->ambulance->create();
    }

    public function store(Request $request)
    {
        return $this->ambulance->store($request);
    }

    public function show(Ambulances $ambulances) {}

    public function edit(Request $ambulances)
    {
        return $this->ambulance->edit($ambulances);
    }

    public function update(Request $request, Ambulances $ambulances)
    {
        return $this->ambulance->update($request);
    }

    public function destroy(Ambulances $ambulances)
    {
        return $this->ambulance->destroy($ambulances);
    }
}
