<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\IReceiptAccount;

class ReceiptAccountController extends Controller
{
    public function __construct(private readonly IReceiptAccount $repo) {}

    public function index()
    {
        return $this->repo->index();
    }

    public function create()
    {
        return $this->repo->create();
    }

    public function store(Request $request)
    {
        return $this->repo->store($request);
    }

    public function show($id)
    {
        // optional: could be implemented in the repo
    }

    public function edit($id)
    {
        return $this->repo->edit((int) $id);
    }

    public function update(Request $request, $id)
    {
        return $this->repo->update($request, (int) $id);
    }

    public function destroy($id)
    {
        return $this->repo->destroy((int) $id);
    }
}
