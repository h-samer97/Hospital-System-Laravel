<?php

namespace App\Repositories;

use App\Interfaces\IService;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;

class ServiceRepository implements IService
{
    public function index(): Response
    {
        $services = Service::query()
        ->select('id', 'name', 'description', 'price', 'is_active', 'created_at')
        ->latest()
        ->get()
        ->map(function(Service $service) {
            return [
                'id' => $service->id,
                'name' => $service->name,
                'description' => $service->description,
                'price' => $service->price,
                'is_active' => $service->is_active,
                'created_at' => $service->created_at,
                // URLs
                'url_store' => route('services.store'),
                'url_update' => route('services.update', $service->id),
                'url_delete' => route('services.destroy', $service->id),
            ];
        });

        return inertia('Services/Index', [
            'services' => $services,
            'url_store' => route('services.store'),
        ]);
    }

    public function store(StoreServiceRequest $request): RedirectResponse
    {   
        try {
            Service::create($request->validated());
            return redirect()->route('services.index')->with('flash', [
            'type'    => 'success',
            'message' => 'Service added successfully',
        ]);

        } catch(Exception $error) {
            return redirect()->back()->with('error', $error->getMessage());
        }

    }

    public function update(UpdateServiceRequest $request, Service $service): RedirectResponse
    {
        $service->update($request->validated());
        return redirect()->route('services.index')->with('flash', [
            'type'    => 'success',
            'message' => 'Service updated successfully',
        ]);
    }

    public function destroy(Service $service): RedirectResponse
    {
        $service->delete();
        return redirect()->route('services.index')->with('flash', [
            'type'    => 'success',
            'message' => 'Service deleted successfully',
        ]);
    }
}