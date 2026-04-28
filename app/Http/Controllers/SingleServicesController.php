<?php

namespace App\Http\Controllers;

use App\Interfaces\ISingleService;
use App\Models\SingleServices;
use Illuminate\Http\Request;

class SingleServicesController extends Controller
{
    protected ISingleService $singleService;

    public function __construct(ISingleService $singleService)
    {
        $this->singleService = $singleService;
    }

    public function index()
    {
        return $this->singleService->index();
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(Request $request)
    {
        return $this->singleService->store($request);
    }

    public function show(SingleServices $singleServices) {}

    public function edit(SingleServices $singleServices)
    {
        return view('admin.services.edit', compact('singleServices'));
    }

    public function update(Request $request, SingleServices $singleServices)
    {
        return $this->singleService->update($request, $singleServices);
    }

    public function destroy(SingleServices $singleServices)
    {
        return $this->singleService->destroy($singleServices);
    }
}
