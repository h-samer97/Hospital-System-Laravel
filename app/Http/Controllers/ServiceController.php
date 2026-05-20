<?php

namespace App\Http\Controllers;

use App\Interfaces\IService;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    
    public function __construct(
        private readonly IService $service
    ) {}

    public function index()
    {
        return $this->service->index();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(\App\Http\Requests\StoreServiceRequest $request)
    {
        return $this->service->store($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(\App\Http\Requests\UpdateServiceRequest $request, Service $service)
    {
        return $this->service->update($request, $service);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        return $this->service->destroy($service);
    }
}
