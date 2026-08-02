<?php

namespace App\Repositories;

use App\Interfaces\IReceiptAccount;
use App\Models\ReceiptAccount;
use App\Models\Patients as Patient;
use App\Models\Patients;
use App\Services\PrintService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response as FacadesResponse;
use Inertia\Inertia;
use Inertia\Response;
use Response as GlobalResponse;

class ReceiptAccountRepository implements IReceiptAccount
{

  public function __construct(
    private readonly PrintService $print
  ) {}

  public function index(): Response
  {
    $receipts = ReceiptAccount::with('patient:id,name')
      ->select('id', 'date', 'patient_id', 'debit', 'description', 'created_at')
      ->latest()
      ->paginate(15)
      ->through(fn(ReceiptAccount $receipt) => [
        'id' => $receipt->id,
        'date' => $receipt->date->format('Y-m-d'),
        'patient' => $receipt->patient?->name,
        'patient_id' => $receipt->patient_id,
        'debit' => $receipt->debit,
        'description' => $receipt->description,
        'created_at' => $receipt->created_at,
        'urls' => [
          'update' => route('receipt.update', $receipt->id),
          'destroy' => route('receipt.destroy', $receipt->id),
          'print' => $this->print->generateSigneURL($receipt, 'Receipt.show'),
          'download' => $this->print->generateSigneURL($receipt, 'Receipt.download'),
        ]
      ]);

    return Inertia::render('Receipts/Index', [
      'receipts' => $receipts,
    ]);
  }

  public function create(): Response
  {
    $patients = Patients::select('id', 'name')->get();

    return Inertia::render('ReceiptAccounts/Create', [
      'patients' => $patients,
    ]);
  }

  public function store(Request $request): RedirectResponse
  {
    $data = $request->validate([
      'date' => ['required', 'date'],
      'patient_id' => ['required', 'exists:patients,id'],
      'debit' => ['required', 'numeric'],
      'description' => ['nullable', 'string'],
    ]);

    ReceiptAccount::create($data);

    return redirect()->route('receipt.index')->with('success', 'Receipt created');
  }

  public function edit(int $id): Response
  {
    $receipt = ReceiptAccount::with('patient')->findOrFail($id);
    $patients = Patients::select('id', 'name')->get();

    return Inertia::render('ReceiptAccounts/Edit', [
      'receipt' => $receipt,
      'patients' => $patients,
    ]);
  }

  public function update(Request $request, int $id): RedirectResponse
  {
    $receipt = ReceiptAccount::findOrFail($id);

    $data = $request->validate([
      'date' => ['required', 'date'],
      'patient_id' => ['required', 'exists:patients,id'],
      'debit' => ['required', 'numeric'],
      'description' => ['nullable', 'string'],
    ]);

    $receipt->update($data);

    return redirect()->route('receipt.index')->with('success', 'Receipt updated');
  }

  public function destroy(int $id): RedirectResponse
  {
    $receipt = ReceiptAccount::findOrFail($id);
    $receipt->delete();

    return redirect()->back()->with('success', 'Receipt deleted');
  }

  public function show(ReceiptAccount $receipt): Response
  {

    $receipt->load('patient:id,name,phone,address');

    $this->print->logPrint($receipt, request(), 'view');

    return Inertia::render('Dashboard/Finance/Receipts/Print', [
      'receipt' => [
        'id' => $receipt->id,
        'date' => $receipt->date->format('Y-m-d'),
        'patient' => $receipt->patient?->name,
        'phone' => $receipt->patient?->phone,
        'address' => $receipt->patient?->address,
        'debit' => $receipt->debit,
        'description' => $receipt->description,
        'created_at' => $receipt->created_at->format('Y-m-d H:i'),
      ],
      'print_count' => $receipt->printLogs()->count()
    ]);
  }

  public function download(ReceiptAccount $receipt): Response
  {
    $receipt->load('patient:id,name,phone,address');
    $this->print->logPrint($receipt, \request(), 'download');

    return Inertia::render('Dashboard/Finance/Receipts/Print', [
      'receipt' => [
        'id' => $receipt->id,
        'date' => $receipt->date->format('Y-m-d'),
        'patient' => $receipt->patient?->name,
        'phone' => $receipt->patient?->phone,
        'address' => $receipt->patient?->address,
        'debit' => $receipt->debit,
        'description' => $receipt->description,
        'created_at' => $receipt->created_at->format('Y-m-d H:i'),
      ],
      'print_count' => $receipt->printLogs()->count()
    ]);
  }
}
