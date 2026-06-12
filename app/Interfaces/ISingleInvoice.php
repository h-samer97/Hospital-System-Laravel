<?php

namespace App\Interfaces;

use Illuminate\Http\Request;
use App\Http\Requests\StoreSingleInvoiceRequest;
use App\Http\Requests\UpdateSingleInvoiceRequest;
use App\Models\SingleInvoices;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;


interface ISingleInvoice
{
  public function index(): Response;
  public function store(StoreSingleInvoiceRequest $request): RedirectResponse;
  public function update(UpdateSingleInvoiceRequest $request, SingleInvoices $invoice): RedirectResponse;
  public function destroy(SingleInvoices $invoice): RedirectResponse;
}
