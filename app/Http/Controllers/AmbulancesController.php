<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAmbulanceRequest;
use App\Http\Requests\UpdateAmbulanceRequest;
use App\Interfaces\IAmbulance;
use App\Models\Ambulances;

class AmbulancesController extends Controller
{
    protected IAmbulance $ambulanceRepo;

    public function __construct(IAmbulance $ambulanceRepo)
    {
        $this->ambulanceRepo = $ambulanceRepo;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->ambulanceRepo->index();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('ambulances.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAmbulanceRequest $request)
    {
        return $this->ambulanceRepo->store($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(Ambulances $ambulances)
    {
        return redirect()->route('ambulances.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ambulances $ambulances)
    {
        return $this->ambulanceRepo->edit($ambulances);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAmbulanceRequest $request, Ambulances $ambulances)
    {
        return $this->ambulanceRepo->update($request, $ambulances);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ambulances $ambulances)
    {
        return $this->ambulanceRepo->destroy($ambulances);
    }
}
