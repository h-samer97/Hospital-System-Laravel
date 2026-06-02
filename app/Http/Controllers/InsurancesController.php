<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInsuranceRequest;
use App\Http\Requests\UpdateInsuranceRequest;
use App\Interfaces\IInsurance;
use App\Models\Insurance;
use Illuminate\Http\Request;

class InsurancesController extends Controller
{


    public function __construct(private readonly IInsurance $insurance) {}

    public function index()
    {
        return $this->insurance->index();
    }

    public function store(StoreInsuranceRequest $request)
    {
        return $this->insurance->store($request);
    }

    public function update(UpdateInsuranceRequest $request, Insurance $insurance)
    {
        return $this->insurance->update($request, $insurance);
    }


    public function destroy(Insurance $insurance)
    {
        return $this->insurance->destroy($insurance);
    }
}
