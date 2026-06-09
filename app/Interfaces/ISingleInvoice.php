<?php

namespace App\Interfaces;

use Illuminate\Http\Request;
use App\Models\SingleInvoices;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;


interface ISingleInvoice
{
  public function index(): Response;
  public function store(Request $request): RedirectResponse;
  public function update(Request $request, SingleInvoices $invoice): RedirectResponse;
  public function destroy(SingleInvoices $invoice): RedirectResponse;
}
