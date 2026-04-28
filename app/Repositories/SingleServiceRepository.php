<?php

namespace App\Repositories;

use App\Interfaces\ISingleService;
use App\Models\SingleServices;

class SingleServiceRepository implements ISingleService
{
    public function index()
    {
        $services = SingleServices::all();

        return view('admin.services.index', compact('services'));
    }

    public function store($request)
    {
        try {
            $SingleService = new SingleServices;
            $SingleService->name = $request->name;
            $SingleService->price = $request->price;
            $SingleService->description = $request->description;
            $SingleService->status = 1;
            $SingleService->save();

            session()->flash('add');

            return redirect()->route('SingleService.index');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'An error occurred while creating the service.']);
        }
    }

    public function create(array $data) {}

    public function update($request, $singleServices)
    {
        try {
            $singleServices->name = $request->name;
            $singleServices->price = $request->price;
            $singleServices->description = $request->description;
            $singleServices->status = $request->status;
            $singleServices->save();

            session()->flash('edit');

            return redirect()->route('SingleService.index');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy($singleServices)
    {
        $singleServices->delete();
        session()->flash('delete');

        return redirect()->route('SingleService.index');
    }
}
