<?php

namespace App\Interfaces;

interface ISingleService
{
    public function index();

    public function store($request);

    public function update($request, $singleServices);

    public function destroy($singleServices);
}
