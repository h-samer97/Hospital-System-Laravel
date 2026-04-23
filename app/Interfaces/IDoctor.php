<?php


    namespace App\Interfaces;

interface IDoctor {

    public function index();
    public function create();
    public function store($request);
    public function update($request, $id);
    public function destroy($id);

}