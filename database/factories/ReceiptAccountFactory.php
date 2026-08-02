<?php

namespace Database\Factories;

use App\Models\ReceiptAccount;
use App\Models\Patients;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReceiptAccountFactory extends Factory
{
    protected $model = ReceiptAccount::class;

    public function definition()
    {
        $patient = Patients::inRandomOrder()->first() ?? Patients::factory()->create();

        return [
            'date' => $this->faker->dateTimeBetween('-1 years', 'now')->format('Y-m-d'),
            'patient_id' => $patient->id,
            'debit' => $this->faker->randomFloat(2, 10, 10000),
            'description' => $this->faker->sentence(6),
        ];
    }

     public function forInvoice(int $invoiceId, float $amount): static
    {
        return $this->state([
            'single_invoice_id' => $invoiceId,
            'debit'  => $amount,
            'credit' => 0,
        ]);
    }

    public function forReceipt(int $receiptId, float $amount): static
    {
        return $this->state([
            'receipt_id' => $receiptId,
            'debit'  => 0,
            'credit' => $amount,
        ]);
    }

    public function forPayment(int $paymentId, float $amount): static
    {
        return $this->state([
            'payment_id' => $paymentId,
            'debit'  => $amount,
            'credit' => 0,
        ]);
    }
}
