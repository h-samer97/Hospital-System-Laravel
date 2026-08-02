<?php

use App\Models\Patients;
use App\Models\SingleInvoices;
use App\Services\PatientService;
use App\Models\Doctor;
use App\Models\PatientAccounts;
use App\Models\PaymentAccount;
use App\Models\ReceiptAccount;
use App\Models\Section;
use App\Models\Service;
use Illuminate\Support\Facades\Cache;

BeforeEach(function () {

  uses(Tests\TestCase::class, Illuminate\Foundation\Testing\RefreshDatabase::class);
  Cache::flush();
  $this->serv = app(PatientService::class);
  $this->patient = Patients::factory()->create();
});


describe('financialSummary', function () {


  it('sums total invoices from SingleInvoices', function () {

    $doctor = Doctor::factory()->create();
    $section = Section::factory()->create();
    $service = Service::factory()->create();

    SingleInvoices::create([

      'patient_id' => $this->patient->id,
      'total_with_tax' => 80.00,
      'invoice_date' => now()->format('Y-m-d'),
      'doctor_id'      => $doctor->id,
      'section_id'     => $section->id,
      'service_id'     => $service->id,

    ]);

    SingleInvoices::create([

      'patient_id' => $this->patient->id,
      'total_with_tax' => 20.00,
      'invoice_date' => now()->format('Y-m-d'),
      'doctor_id'      => $doctor->id,
      'section_id'     => $section->id,
      'service_id'     => $service->id,

    ]);

    $summary = $this->serv->financialSummary($this->patient);

    expect($summary['total_invoiced'])->toBe(100.00);
  });

  it('sums total Receipt from Receipt Account', function () {

    ReceiptAccount::create([
      'patient_id' => $this->patient->id,
      'debit' => (float) 90.00,
      'date' => now(),
      'description' => 'lorem'
    ]);

    $summary = $this->serv->financialSummary($this->patient);

    expect($summary['total_receipt'])->toBe(90.00);
  });

  it('sums total Payment from Paymant Account', function () {

    PaymentAccount::create([

      'patient_id' => $this->patient->id,
      'amount' => 100.00,
      'date' => now()

    ]);

    $summary = $this->serv->financialSummary($this->patient);

    expect($summary['total_payments'])->toBe(100.00);
  });

  it('calculates balance as debit minus create', function () {

    PatientAccounts::create([

      'patient_id' => $this->patient->id,
      'debit' => 80.00,
      'credit' => 0.00,
      'date' => now()

    ]);

    PatientAccounts::create([

      'patient_id' => $this->patient->id,
      'debit' => 0.00,
      'credit' => 20.00,
      'date' => now()

    ]);

    $summary = $this->serv->financialSummary($this->patient);

    expect($summary['debit_total'])->toBe(80.00)
      ->and($summary['credit_total'])->toBe(20.00)
      ->and($summary['balance'])->toBe(60.00);
  });

  # NOT Working
  it('labels balance as Debtor when positive', function () {

    PatientAccounts::create([
      'patient_id' => $this->patient->id,
      'debit' => 500.00,
      'credit' => 0.00,
      'date' => now()
    ]);

    $summary = $this->serv->financialSummary($this->patient);

    expect($summary['is_debtor'])->toBeTrue();
  });

  # Not Working
  it('lables balance as creditor when negative', function () {

    PatientAccounts::create([
      'patient_id' => $this->patient->id,
      'debit' => 0.00,
      'credit' => 50.00,
      'date' => now()
    ]);

    $summary = $this->serv->financialSummary($this->patient);

    expect($summary['is_debtor'])->toBeFalse();
  });

  it('lables balance as Settled when Zero', function () {

    PatientAccounts::create([
      'patient_id' => $this->patient->id,
      'debit' => 50.00,
      'credit' => 50.00,
      'date' => now()
    ]);

    $summary = $this->serv->financialSummary($this->patient);

    expect($summary['balance'])->toBe(0.00);
  });
});
