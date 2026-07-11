<?php

namespace App\Repositories;

use App\Interfaces\IReceiptAccount;
use App\Models\ReceiptAccount;
use App\Models\Patients as Patient;
use App\Models\Patients;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;

class ReceiptAccountRepository implements IReceiptAccount
{
  public function index(): Response
  {
    $receipts = ReceiptAccount::with('patient')->latest()->get();

    return inertia('Receipts/Index', [
      'receipts' => $receipts,
    ]);
  }

  public function create(): Response
  {
    $patients = Patients::select('id', 'name')->get();

    return inertia('ReceiptAccounts/Create', [
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

    return redirect()->route('receipt_accounts.index')->with('success', 'Receipt created');
  }

  public function edit(int $id): Response
  {
    $receipt = ReceiptAccount::with('patient')->findOrFail($id);
    $patients = Patients::select('id', 'name')->get();

    return inertia('ReceiptAccounts/Edit', [
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

    return redirect()->route('receipt_accounts.index')->with('success', 'Receipt updated');
  }

  public function destroy(int $id): RedirectResponse
  {
    $receipt = ReceiptAccount::findOrFail($id);
    $receipt->delete();

    return redirect()->back()->with('success', 'Receipt deleted');
  }
}
