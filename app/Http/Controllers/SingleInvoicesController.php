<?php

namespace App\Http\Controllers;

use App\Models\SingleInvoices;
use App\Interfaces\ISingleInvoice;
use App\Http\Requests\StoreSingleInvoiceRequest;
use App\Http\Requests\UpdateSingleInvoiceRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response as HttpResponse;
use Inertia\Response;

class SingleInvoicesController extends Controller
{
    public function __construct(private readonly ISingleInvoice $singleInvoiceRepo) {}

    public function index(): Response
    {
        return $this->singleInvoiceRepo->index();
    }

    public function store(StoreSingleInvoiceRequest $request): RedirectResponse
    {
        return $this->singleInvoiceRepo->store($request);
    }

    public function update(UpdateSingleInvoiceRequest $request, SingleInvoices $invoice): RedirectResponse
    {
        return $this->singleInvoiceRepo->update($request, $invoice);
    }

    public function destroy(SingleInvoices $invoice): RedirectResponse
    {
        return $this->singleInvoiceRepo->destroy($invoice);
    }

    public function show(SingleInvoices $invoice): Response
    {
        return $this->singleInvoiceRepo->show($invoice);
    }

    public function download(SingleInvoices $invoice): Response
    {
        return $this->singleInvoiceRepo->download($invoice);
    }

    // keep other methods as no-op to satisfy resource controller
    public function create() {}
    public function edit(SingleInvoices $invoice) {}
}
