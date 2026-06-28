<?php

namespace App\Services;

use App\Jobs\GeneratePaymentReceiptJob;
use App\Models\FundAccounts;
use Illuminate\Support\Facades\DB;
use App\Models\PatientAccounts as PatientAccount;
use App\Models\PaymentAccount;
use Log;
use Throwable;

class PaymentService
{

  public function store(array $data, int $adminId): PaymentAccount
  {

    $payment = DB::transaction(function () use ($data, $adminId) {


      # Step 1 => Generate a bond
      $payment = PaymentAccount::create([

        'date' => now()->toDateString(),
        'patient_id' => $data['patient_id'],
        'description' => $data['description'],
        'amount'      => $data['amount']

      ]);

      # Step 2 => The Money Out!
      FundAccounts::create([
        'date' => now()->toDateString(),
        'payment_id' => $payment->id,
        'debit' => 0.00,
        'credit' => $payment->amount
      ]);

      # Step 3 => the patient account is increment  
      PatientAccount::create([
        'date'       => now()->toDateString(),
        'patient_id' => $payment->patient_id,
        'payment_id' => $payment->id,
        'debit'      => $payment->amount,
        'credit'     => 0.00,
      ]);

      GeneratePaymentReceiptJob::dispatch($payment->id)->afterCommit();

      Log::channel('finance')->info('Payment Created', [
        'payment_id' => $payment->id,
        'admin_id' => $adminId,
        'amount' => $payment->amount
      ]);


      return $payment;
    });
    return $payment;
  }

  public function update(PaymentAccount $payment, array $data, int $adminId): PaymentAccount
  {

    $payment = DB::transaction(function () use ($payment, $data, $adminId) {

      $payment->update([
        'amount' => $data['amount'],
        'description' => $data['description'],
        'patient_id' => $data['patient_id']
      ]);

      $payment->fundAccount()->updateOrCreate(
        ['payment_id' => $payment->id],
        [
          'date'   => now()->toDateString(),
          'debit'  => 0.00,
          'credit' => $data['amount'],
        ]
      );

      $payment->patientAccount()->updateOrCreate(
        ['payment_id' => $payment->id],
        [
          'date'       => now()->toDateString(),
          'patient_id' => $data['patient_id'],
          'debit'      => $data['amount'],
          'credit'     => 0.00,
        ]
      );

      Log::channel('finance')->info('Payment updated', [
        'payment_id' => $payment->id,
        'admin_id'   => $adminId,
      ]);
    });

    return $payment->fresh();
  }

  public function destroy(PaymentAccount $payment, int $adminId): void
  {

    try {
      DB::transaction(function () use ($payment) {
        $payment->delete();
      });
      Log::channel('finance')->info('Payment Deleted', [
        'payment_id' => $payment->id,
        'admin_id' => $adminId
      ]);
    } catch (Throwable $error) {
      Sentry\captureException($error);
      throw $error;
    }
  }
}
