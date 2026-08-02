<?php


namespace App\Repositories;


use App\Interfaces\ISingleInvoice;
use App\Models\Doctor;
use App\Models\Patients;
use App\Models\SingleInvoices;
use App\Services\InvoiceService;
use App\Models\Service;
use App\Http\Requests\StoreSingleInvoiceRequest;
use App\Http\Requests\UpdateSingleInvoiceRequest;
use App\Services\PrintService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class SingleInvoiceRepository implements ISingleInvoice
{

  public function __construct(
    private readonly InvoiceService $invoiceService,
    private readonly PrintService $print
  ) {}

  public function index(): Response
  {
    $singleInvoices = SingleInvoices::with([
      'patient:id,name',
      'doctor.section:id,name',
      'doctor:id,name,section_id',
      'service:id,name',
    ])
      ->select(
        'id',
        'invoice_date',
        'patient_id',
        'doctor_id',
        'service_id',
        'price',
        'discount_value',
        'tax_rate',
        'tax_value',
        'total_with_tax',
        'type',
        'created_at'
      )
      ->latest()
      ->get()
      ->map(fn(SingleInvoices $inv) => [
        'id'             => $inv->id,
        'invoice_date'   => $inv->invoice_date->format('Y-m-d'),
        'patient'        => $inv->patient?->name,
        'doctor'         => $inv->doctor?->name,
        'section'        => $inv->doctor?->section?->name,
        'service'        => $inv->service?->name,
        'price'          => $inv->price,
        'discount_value' => $inv->discount_value,
        'tax_rate'       => $inv->tax_rate,
        'tax_value'      => $inv->tax_value,
        'total_with_tax' => $inv->total_with_tax,
        'type'           => $inv->type,
        'type_label'     => $inv->type_label,
        'created_at'     => $inv->created_at,
        'urls' => [
          'update'  => route('single_invoices.update',  $inv->id),
          'destroy' => route('single_invoices.destroy', $inv->id),
          'print'    => $this->print->generateSigneURL($inv, 'single_invoices.show'),
          'download' => $this->print->generateSigneURL($inv, 'single_invoices.download'),
        ],
      ]);

    return Inertia::render('Invoices/Index', [
      'invoices'  => $singleInvoices,
      'store_url' => route('single_invoices.store'),
      'patients' => Patients::where('is_active', true)->select('name', 'id')->get(),
      'doctors' => Doctor::with(['section:id,name'])
        ->where('is_active', true)
        ->select('id', 'name', 'section_id')
        ->get()
        ->map(fn($d) => [
          'id'          => $d->id,
          'name'        => $d->name,
          'section_id'  => $d->section_id,
          'section_name' => $d->section?->name,
        ]),
      'services' => Service::where('is_active', true)
        ->select('id', 'name', 'price')
        ->get(),
    ]);
  }

  public function store(StoreSingleInvoiceRequest $request): RedirectResponse
  {

    $this->invoiceService->store($request->validated());

    return redirect()->route('single_invoices.index')->with('flash', [
      'type'    => 'success',
      'message' => 'Invoice created successfully',
    ]);
  }

  public function update(UpdateSingleInvoiceRequest $request, SingleInvoices $invoice): RedirectResponse
  {
    $this->invoiceService->update($invoice, $request->validated());

    return redirect()->route('single_invoices.index')->with('flash', [
      'type'    => 'success',
      'message' => 'Invoice updated successfully',
    ]);
  }

  public function destroy(SingleInvoices $invoice): RedirectResponse
  {
    $this->invoiceService->destroy($invoice);

    return redirect()->route('single_invoices.index')->with('flash', [
      'type'    => 'success',
      'message' => 'Invoice deleted successfully',
    ]);
  }

  public function show(SingleInvoices $invoice): Response
  {

    $invoice->load(
      [
        'patient:name,id,phone,address',
        'doctor:id,name,section_id',
        'doctor.section:name,id',
        'service:name,id'
      ]
    );

    $this->print->logPrint($invoice, \request(), 'view');

    return Inertia::render('Dashboard/Invoices/SingleInvoices/Print', [
      'invoice' => [
        'id'             => $invoice->id,
        'invoice_date'   => $invoice->invoice_date->format('Y-m-d'),
        'patient'        => $invoice->patient?->name,
        'phone'          => $invoice->patient?->phone,
        'doctor'         => $invoice->doctor?->name,
        'section'        => $invoice->doctor?->section?->name,
        'service'        => $invoice->service?->name,
        'price'          => $invoice->price,
        'discount_value' => $invoice->discount_value,
        'tax_rate'       => $invoice->tax_rate,
        'tax_value'      => $invoice->tax_value,
        'total_with_tax' => $invoice->total_with_tax,
        'type'           => $invoice->type,
        'type_label'     => $invoice->type_label,
        'created_at'     => $invoice->created_at->format('Y-m-d H:i'),
      ],
      'print_count' => $invoice->printLogs()->count(),
    ]);
  }

  public function download(SingleInvoices $invoice): Response
  {
    $invoice->load(['patient', 'doctor.section', 'service']);
    $this->print->logPrint($invoice, request(), 'download');
    
    return Inertia::render('Dashboard/Invoices/SingleInvoices/Print', [
      'invoice' => [
        'id'             => $invoice->id,
        'invoice_date'   => $invoice->invoice_date->format('Y-m-d'),
        'patient'        => $invoice->patient?->name,
        'phone'          => $invoice->patient?->phone,
        'doctor'         => $invoice->doctor?->name,
        'section'        => $invoice->doctor?->section?->name,
        'service'        => $invoice->service?->name,
        'price'          => $invoice->price,
        'discount_value' => $invoice->discount_value,
        'tax_rate'       => $invoice->tax_rate,
        'tax_value'      => $invoice->tax_value,
        'total_with_tax' => $invoice->total_with_tax,
        'type'           => $invoice->type,
        'type_label'     => $invoice->type_label,
        'created_at'     => $invoice->created_at->format('Y-m-d H:i'),
      ],
      'print_count' => $invoice->printLogs()->count(),
    ]);
  }
}
