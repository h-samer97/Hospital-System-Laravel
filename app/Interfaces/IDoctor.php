<?php

namespace App\Interfaces;

interface IDoctor
{
    public function index();

    public function update_password($request);

    public function update_status($id);

    public function create();

    public function show($id);

    public function edit($id);

    public function store($request);

    public function update($request, $id);

    public function destroy($id);
}
