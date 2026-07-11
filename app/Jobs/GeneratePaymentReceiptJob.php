<?php

namespace App\Jobs;

use App\Models\PaymentAccount;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log;

class GeneratePaymentReceiptJob implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    public function __construct(public readonly int $paymentAccountId) {}

    public function handle(): void
    {
        $payment = PaymentAccount::with('patient')->find($this->paymentAccountId);
        if (!$payment) {
            Log::channel('finance')->warning('Payment not found for receipt generation', [
                'payment_id' => $this->paymentAccountId
            ]);
            return;
        }

        Log::channel('finance')->info('Payment receipt generated', [
            'payment_id' => $payment->id,
            'patient_id' => $payment->patient_id,
            'amount'     => $payment->amount,
        ]);
    }

    public function failed(\Throwable $e): void
    {
        \Sentry\captureException($e);
        Log::channel('finance')->error('Receipt generation failed permanently', [
            'payment_id' => $this->paymentAccountId,
            'error'      => $e->getMessage(),
        ]);
    }
}
