<?php

namespace App\Services;

use App\Models\FundAccounts;
use App\Models\PatientAccounts;
use App\Models\SingleInvoices;

class InvoiceService
{
    public function calculate(float $total, float $tax, float $discount): array
    {

        $subTotal = $discount > 0 ? \max(0, $total - $discount) : $total;

        $tax_value = $subTotal * ($tax / 100);

        $total_with_tax = $subTotal + $tax_value;

        return [
            'sub_total' => \round($subTotal, 2),
            'tax_value' => \round($tax_value, 2),
            'total' => \round($total_with_tax, 2),
        ];
    }

    public function store(array $data): SingleInvoices
    {
        return \DB::transaction(function () use ($data) {

            $calc = $this->calculate((float)$data['price'], (float)$data['tax_rate'], (float)$data['discount_value']);

            $invoice = SingleInvoices::create([
                'invoice_date' => now()->toDateString(),
                'patient_id'     => $data['patient_id'],
                'doctor_id'      => $data['doctor_id'],
                'section_id'     => $data['section_id'],
                'service_id'     => $data['service_id'],
                'price'          => $data['price'],
                'discount_value' => $data['discount_value'],
                'tax_rate'       => $data['tax_rate'],
                'tax_value'      => $calc['tax_value'],
                'total_with_tax' => $calc['total'],
                'type'           => $data['type'],
            ]);
            $this->createAccountEntry($invoice);
            return $invoice;
        });
    }



    public function update(SingleInvoices $invoice, array $data): SingleInvoices
    {
        return \DB::transaction(function () use ($invoice, $data) {

            $calc = $this->calculate((float)$data['price'], (float)$data['tax_rate'], (float)$data['discount_value']);

            $invoice->update([

                'invoice_date'   => now()->toDateString(),
                'patient_id'     => $data['patient_id'],
                'doctor_id'      => $data['doctor_id'],
                'section_id'     => $data['section_id'],
                'service_id'     => $data['service_id'],
                'price'          => $data['price'],
                'discount_value' => $data['discount_value'],
                'tax_rate'       => $data['tax_rate'],
                'tax_value'      => $calc['tax_value'],
                'total_with_tax' => $calc['total'],
                'type'           => $data['type'],

            ]);

            if ($invoice->fundAccount) {
                $invoice->fundAccount()->delete();
            }
            if ($invoice->patientAccount) {
                $invoice->patientAccount()->delete();
            }
            $this->createAccountEntry($invoice->fresh());

            return $invoice->fresh();
        });
    }

    public function createAccountEntry(SingleInvoices $invoice): mixed
    {
        return match ($invoice->type) {
            'cash' => FundAccounts::create([
                'date'              => now()->toDateString(),
                'single_invoice_id' => $invoice->id,
                'debit'             => $invoice->total_with_tax,
                'credit'            => 0,
            ]),
            'deferred' => PatientAccounts::create([
                'date'              => now()->toDateString(),
                'single_invoice_id' => $invoice->id,
                'patient_id'        => $invoice->patient_id,
                'debit'             => $invoice->total_with_tax,
                'credit'            => 0,
            ]),
            default => throw new \InvalidArgumentException("Unknown invoice type: {$invoice->type}"),
        };
    }
    public function destroy(SingleInvoices $invoice): void
    {
        \DB::transaction(function () use ($invoice) {
            if ($invoice->fundAccount) {
                $invoice->fundAccount()->delete();
            }
            if ($invoice->patientAccount) {
                $invoice->patientAccount()->delete();
            }
            SingleInvoices::destroy($invoice->id);
        });
    }
}
