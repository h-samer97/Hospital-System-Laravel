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