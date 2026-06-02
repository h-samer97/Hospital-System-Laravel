<?php

  namespace App\Interfaces;

use App\Http\Requests\StoreAmbulanceRequest;
use App\Http\Requests\UpdateAmbulanceRequest;
use App\Models\Ambulances;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;

interface IAmbulance {
          public function index() : Response;
           public function store(StoreAmbulanceRequest $request) : RedirectResponse;
           public function edit(Ambulances $ambulances) : Response;
           public function update(UpdateAmbulanceRequest $request, Ambulances $ambulances) : RedirectResponse;
           public function destroy(Ambulances $ambulances) : RedirectResponse;
}