<?php

  namespace App\Services;


  use App\Models\ReceiptAccount;
  use App\Models\FundAccounts;
  use App\Models\PatientAccounts;
  use Illuminate\Support\Facades\DB;

class ReceiptService {

  public function store(array $data) : ReceiptAccount {

      return DB::transaction(function () use ($data) {

        # Create Receipt
        $receipt = ReceiptAccount::create([
            'date' => now()->toDateTimeLocalString(),
            'patient_id' => $data['patient_id'],
            'debit' => $data['debit'],
            'description' => $data['description']
        ]);

        # Create FundAccount
        $fundAcc = FundAccounts::create([
            'date' => now()->toDateString(),
            'receipt_id' => $receipt->id,
            'debit' => $receipt->debit,
            'credit' => 0.00
        ]);

        # Patient Account
        $patientAcc = PatientAccounts::create([
            'date' => now()->toDateString(),
            'debit' => 0.00,
            'credit' => $receipt->credit
        ]);

      return $receipt;

      });

  }

  public function update(array $data, ReceiptAccount $receipt) : ReceiptAccount {

      return DB::transaction(function () use ($data, $receipt) {

          $receiptAcc = ReceiptAccount::update([
            'patient_id'  => $data['patient_id'],
            'debit'       => $data['debit'],
            'description' => $data['description'],
          ]); 

          $receipt->fundAccount?->update([
                'date'   => now()->toDateString(),
                'debit'  => $data['debit'],
                'credit' => 0.00,
            ]);

            // 3. تحديث قيد المريض
            $receipt->patientAccount?->update([
                'date'       => now()->toDateString(),
                'patient_id' => $data['patient_id'],
                'debit'      => 0.00,
                'credit'     => $data['debit'],
            ]);

            return $receipt->fresh();

      });

  }

  public function destroy(ReceiptAccount $receipt) : void {
        DB::transaction(fn() => $receipt->delete());
  }

  # Calculate Total Pays
  public function patientBalance(int $id) : array {

    $totalDebit = PatientAccounts::where('patient_id','=', $id,true)
    ->whereNotNull('receipt_id')
    ->sum('debit');

    $totalCredit = PatientAccounts::where('patient_id', '=', $id, true)
    ->whereNotNull('receipt_id')
    ->sum('credit');

    return [
        'totalDebit' => $totalDebit,
        'totalCredit' => $totalCredit,
        'balance'       => ($totalDebit - $totalCredit),
    ];

  }

}