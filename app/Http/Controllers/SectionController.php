<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;
use App\Interfaces\ISections;

class SectionController extends Controller
{

    public ISections $section;

    public function __construct(ISections $section)
    {
        $this->section = $section;
    }
    
    public function index()
    {
        return $this->section->index();
    }

    public function create()
    {
        return $this->section->create();
    }

   
    public function store(Request $request)
    {
        return $this->section->store($request);
    }

     public function update(Request $request)
    {
        return $this->section->update($request);
    }

    public function edit()
    {
        return $this->section->edit($section);
    }

   
    public function destroy($id)
    {
        return $this->section->destroy($section);
    }
}
