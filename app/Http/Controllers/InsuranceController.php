<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InsuranceController extends Controller
{
    protected IIncurance $incurance;

    public function __construct(IIncurance $incurance)
    {
        $this->incurance = $incurance;
    }

    public function index()
    {
        return $this->incurance->index();
    }

    public function create()
    {
        return $this->incurance->create();
    }

    public function store(StoreInsuranceRequest $request)
    {
        return $this->incurance->store($request);
    }

    public function edit($id)
    {
        return $this->incurance->edit($id);
    }

    public function update(UpdateInsuranceRequest $request)
    {
        return $this->incurance->update($request);
    }

    public function destroy($id)
    {
        return $this->incurance->destroy($id);
    }
}
