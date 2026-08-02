<?php

use App\Models\Admin;
use App\Models\Patients;
use App\Models\PaymentAccount;
use App\Models\PrintLog;
use App\Services\PrintService;

beforeEach(function () {
  $this->admin = Admin::factory()->create();
  $this->actingAs($this->admin, 'admins');
  $this->service = app(PrintService::class);
  $this->withoutVite();
});

describe('Payment Show', function () {

  it('returns 200 with valid sign url', function () {

    $payment = PaymentAccount::factory()->create();
    $url = $this->service->generateSigneURL($payment, 'payments.show');

    $this->get($url)->assertStatus(200);
  });

  it('renders correct Inertia Component', function () {

    $payment = PaymentAccount::factory()->create();
    $url = $this->service->generateSigneURL($payment, 'payments.show');

    $this->get($url)->assertInertia(
      fn($page) => $page
        ->component('Payments/Print')
        ->has('payment')
        ->has('payment.id')
        ->has('payment.description')
        ->has('payment.amount')
        ->has('print_count')

    );
  });

  it('returns correct payment data', function () {

    $patient = Patients::factory()->create(['name' => 'Ali']);

    $payment_account = PaymentAccount::factory()->create([
      'patient_id' => $patient->id,
      'amount' => '250.00',
      'description' => 'Test PA'
    ]);

    $url = $this->service->generateSigneURL($payment_account, 'payments.show');

    $this->get($url)->assertInertia(
      fn($page) =>
      $page->where('payment.id', $payment_account->id)
        ->where('payment.patient', 'Ali')
        ->where('payment.amount', '250.00')
        ->where('payment.description', 'Test PA')
    );
  });

  it('creates a PrintLog on view', function () {
    $payment = PaymentAccount::factory()->create();
    $url     = $this->service->generateSigneURL($payment, 'payments.show');

    $this->get($url);

    expect(PrintLog::count())->toBe(1);
    expect(PrintLog::first()->action)->toBe('view');
  });

  it('increments print_count on each view', function () {
    $payment = PaymentAccount::factory()->create();
    $url     = $this->service->generateSigneURL($payment, 'payments.show');

    $this->get($url);
    $this->get($url);
    $this->get($url);

    $this->get($url)->assertInertia(
      fn($page) =>
      $page->where('print_count', 4)
    );
  });

  it('returns 403 with unsigned URL', function () {
    $payment = PaymentAccount::factory()->create();

    // رابط مباشر بدون signature
    $this->get(route('payments.show', $payment))
      ->assertStatus(403);
  });

  it('returns 403 with expired signed URL', function () {
    $payment = PaymentAccount::factory()->create();

    // URL انتهت صلاحيته منذ ساعة
    $expiredUrl = URL::temporarySignedRoute(
      'payments.show',
      now()->subHour(),
      ['payment' => $payment->id]
    );

    $this->get($expiredUrl)->assertStatus(403);
  });

  it('returns 403 with tampered signed URL', function () {
    $payment = PaymentAccount::factory()->create();
    $url     = $this->service->generateSigneURL($payment, 'payments.show');

    // تزوير الـ signature
    $tampered = preg_replace('/signature=[^&]+/', 'signature=hacked123', $url);

    $this->get($tampered)->assertStatus(403);
  });

  it('returns 404 for non-existent payment', function () {
    $url = URL::temporarySignedRoute(
      'payments.show',
      now()->addMinutes(30),
      ['payment' => 99999]
    );

    $this->get($url)->assertStatus(404);
  });

  it('redirects unauthenticated users', function () {
    auth('admins')->logout();

    $payment = PaymentAccount::factory()->create();
    $url     = $this->service->generateSigneURL($payment, 'payments.show');

    $this->get($url)->assertRedirect(route('login'));
  });
});

// =====================================================
// download (PDF)
// =====================================================

describe('Payment download (PDF)', function () {

    it('returns print view for download', function () {
        $payment = PaymentAccount::factory()->create();
        $url     = $this->service->generateSigneURL($payment, 'payments.download');

        $this->get($url)
             ->assertStatus(200);
    });

    it('logs download action', function () {
        $payment = PaymentAccount::factory()->create();
        $url     = $this->service->generateSigneURL($payment, 'payments.download');

        $this->get($url);

        expect(PrintLog::first()->action)->toBe('download');
    });

    it('returns 403 with unsigned URL for download', function () {
        $payment = PaymentAccount::factory()->create();

        $this->get(route('payments.download', $payment))
              ->assertStatus(403);
    });
});
