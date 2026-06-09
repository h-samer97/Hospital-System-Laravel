<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SingleInvoices;
use App\Models\FundAccounts;
use App\Models\PatientAccounts;
use Carbon\Carbon;

class SingleInvoicesTableSeeder extends Seeder
{
  public function run(): void
  {
    // create a bunch of invoices
    SingleInvoices::factory()->count(300)->create()->each(function (SingleInvoices $invoice) {
      $date = Carbon::parse($invoice->invoice_date)->toDateString();

      // create patient account charge (invoice)
      PatientAccounts::create([
        'date' => $date,
        'single_invoice_id' => $invoice->id,
        'patient_id' => $invoice->patient_id,
        'debit' => $invoice->total_with_tax,
        'credit' => 0,
        'notes' => 'Invoice charge',
      ]);

      // if payment is immediate (cash) create fund and patient payment record
      if ($invoice->type === 'cash') {
        FundAccounts::create([
          'date' => $date,
          'single_invoice_id' => $invoice->id,
          'debit' => 0,
          'credit' => $invoice->total_with_tax,
          'notes' => 'Payment - cash',
        ]);

        PatientAccounts::create([
          'date' => $date,
          'single_invoice_id' => $invoice->id,
          'patient_id' => $invoice->patient_id,
          'debit' => 0,
          'credit' => $invoice->total_with_tax,
          'notes' => 'Payment received',
        ]);
      }
    });
  }
}
