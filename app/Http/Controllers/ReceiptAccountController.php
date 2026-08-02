<?php

namespace App\Http\Controllers;

use App\Interfaces\IReceiptAccount;
use App\Models\ReceiptAccount;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Inertia\Response;

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

    public function show(ReceiptAccount $receipt): Response
    {
        return $this->repo->show($receipt);
    }

    public function edit($id)
    {
        return $this->repo->edit((int) $id);
    }

    public function update(Request $request, $id)
    {
        return $this->repo->update($request, (int) $id);
    }

    public function destroy($id): RedirectResponse
    {
        return $this->repo->destroy((int) $id);
    }

    public function download(ReceiptAccount $receipt): Response
    {
        return $this->repo->download($receipt);
    }
}
