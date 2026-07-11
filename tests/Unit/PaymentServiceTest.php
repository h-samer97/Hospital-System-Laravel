<?php

use App\Jobs\GeneratePaymentReceiptJob;
use App\Models\FundAccounts as FundAccount;
use App\Models\Patients as Patient;
use App\Models\PatientAccounts as PatientAccount;
use App\Models\PaymentAccount;
use App\Services\PaymentService;
use Illuminate\Support\Facades\Queue;

// ===== اختبار منطق الأعمال في PaymentService =====

beforeEach(function () {
  // قبل كل اختبار: instance نظيف من الـ Service
  $this->service = app(PaymentService::class);
  $this->patient = Patient::factory()->create();
});

// ===== store() =====

it('creates a payment account record', function () {
  $this->service->store([
    'patient_id'  => $this->patient->id,
    'amount'      => 500.00,
    'description' => 'Test payment',
  ], adminId: 1);

  expect(PaymentAccount::count())->toBe(1);

  $payment = PaymentAccount::first();
  expect($payment->patient_id)->toBe($this->patient->id)
    ->and((float) $payment->amount)->toBe(500.00)
    ->and($payment->description)->toBe('Test payment');
});

it('creates fund account entry with credit on payment', function () {
  $this->service->store([
    'patient_id'  => $this->patient->id,
    'amount'      => 300.00,
    'description' => 'Disbursement',
  ], adminId: 1);

  $fund = FundAccount::first();

  expect($fund)->not->toBeNull()
    // الصندوق يصرف → credit يزيد، debit = 0
    ->and((float) $fund->credit)->toBe(300.00)
    ->and((float) $fund->debit)->toBe(0.00);
});

it('creates patient account entry with debit on payment', function () {
  $this->service->store([
    'patient_id'  => $this->patient->id,
    'amount'      => 300.00,
    'description' => 'Disbursement',
  ], adminId: 1);

  $patientAccount = PatientAccount::first();

  expect($patientAccount)->not->toBeNull()
    ->and($patientAccount->patient_id)->toBe($this->patient->id)
    // دين المريض يزيد → debit يزيد، credit = 0
    ->and((float) $patientAccount->debit)->toBe(300.00)
    ->and((float) $patientAccount->credit)->toBe(0.00);
});

it('dispatches GeneratePaymentReceiptJob after store', function () {
  Queue::fake();

  $this->service->store([
    'patient_id'  => $this->patient->id,
    'amount'      => 200.00,
    'description' => 'Test',
  ], adminId: 1);

  Queue::assertPushed(GeneratePaymentReceiptJob::class, function ($job) {
    $payment = PaymentAccount::first();
    return $job->paymentAccountId === $payment->id;
  });
});

it('rolls back all records if an error occurs during store', function () {
  // نُجبر الـ PatientAccount على الفشل
  $this->mock(PatientAccount::class)
    ->shouldReceive('create')
    ->andThrow(new \RuntimeException('DB Error'));

  try {
    $this->service->store([
      'patient_id'  => $this->patient->id,
      'amount'      => 100.00,
      'description' => 'Test',
    ], adminId: 1);
  } catch (\Exception) {
  }

  // DB::transaction يضمن أن لا شيء حُفظ
  expect(PaymentAccount::count())->toBe(0)
    ->and(FundAccount::count())->toBe(0)
    ->and(PatientAccount::count())->toBe(0);
});

// ===== update() =====

it('updates payment and related account entries', function () {
  $payment = PaymentAccount::factory()->create([
    'patient_id' => $this->patient->id,
    'amount'     => 500.00,
  ]);

  // إنشاء القيود الأولية
  FundAccount::create([
    'date' => now()->toDateString(),
    'payment_id' => $payment->id,
    'credit' => 500.00,
    'debit'  => 0.00,
  ]);
  PatientAccount::create([
    'date'       => now()->toDateString(),
    'patient_id' => $this->patient->id,
    'payment_id' => $payment->id,
    'debit'      => 500.00,
    'credit'     => 0.00,
  ]);

  // تعديل إلى 750
  $this->service->update($payment, [
    'patient_id'  => $this->patient->id,
    'amount'      => 750.00,
    'description' => 'Updated',
  ], adminId: 1);

  expect((float) $payment->fresh()->amount)->toBe(750.00)
    ->and((float) $payment->fundAccount->fresh()->credit)->toBe(750.00)
    ->and((float) $payment->patientAccount->fresh()->debit)->toBe(750.00);
});

// ===== destroy() =====

it('soft deletes the payment account', function () {
  $payment = PaymentAccount::factory()->create([
    'patient_id' => $this->patient->id,
  ]);

  $this->service->destroy($payment, adminId: 1);

  // السجل موجود في DB لكن deleted_at مُعبَّأ
  expect(PaymentAccount::count())->toBe(0);
  expect(PaymentAccount::withTrashed()->count())->toBe(1);
});
