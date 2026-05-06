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
        return $this->singleService->create();
    }

    public function store(Request $request)
    {
        return $this->singleService->store($request);
    }

    public function show(SingleServices $singleServices) {}

    public function edit($id)
    {
        return $this->singleService->edit($id);
    }

    public function update(Request $request, $id)
    {
        return $this->singleService->update($request, $id);
    }

    public function destroy($id)
    {
        return $this->singleService->destroy($id);
    }
}
