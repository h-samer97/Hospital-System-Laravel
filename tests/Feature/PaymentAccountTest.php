<?php

use App\Models\Admin;
use App\Models\PaymentAccount;
use App\Models\FundAccounts as FundAccount;
use App\Models\PatientAccounts as PatientAccount;
use App\Models\Patients as Patient;
use App\Services\PaymentService;
use Illuminate\Support\Facades\Vite;
use Inertia\Testing\AssertableInertia;

beforeEach(function () {

  $this->admin = Admin::factory()->create();
  $this->actingAs($this->admin, 'admins');

  $this->withoutVite();
});

describe('index', function () {

  it('returns 200 for authenticated admin', function () {

    $this->withoutExceptionHandling();

    $response = $this->get(route('payments.index'));
    $response->assertStatus(200);
  });

  it('returns paginated payments via Inertia', function () {
    PaymentAccount::factory(20)->create();

    $response = $this->get(route('payments.index'));

    $response->assertInertia(
      fn($page) =>
      $page->component('payments/index', false)
        ->has('payments')
        ->has('payments.data')
        ->has('payments.total')
        ->has('payments.current_page')
        ->has('patients')
        ->has('store_url')
    );
  });

  it('paginates to 15 per page', function () {
    PaymentAccount::factory(20)->create();

    $response = $this->get(route('payments.index'));

    $response->assertInertia(
      fn($page) =>
      $page->where('payments.per_page', 15)
    );
  });

  it('redirects unauthenticated users', function () {
    // تسجيل خروج
    auth('admins')->logout();

    $this->get(route('payments.index'))
      ->assertRedirect(route('login'));
  });


  it('sends flash success message', function () {
    $patient = Patient::factory()->create();

    $this->post(route('payments.store'), [
      'patient_id'  => $patient->id,
      'amount'      => 300.00,
      'description' => 'Test',
    ])->assertSessionHas('flash.type', 'success');
  });

  it('returns validation errors for missing fields', function () {
    $this->post(route('payments.store'), [])
      ->assertSessionHasErrors(['patient_id', 'amount']);
  });

  it('rejects amount of zero', function () {
    $patient = Patient::factory()->create();

    $this->post(route('payments.store'), [
      'patient_id'  => $patient->id,
      'amount'      => 0,
      'description' => 'Test',
    ])->assertSessionHasErrors('amount');
  });

  it('rejects negative amount', function () {
    $patient = Patient::factory()->create();

    $this->post(route('payments.store'), [
      'patient_id'  => $patient->id,
      'amount'      => -100,
      'description' => 'Test',
    ])->assertSessionHasErrors('amount');
  });

  it('rejects non-existent patient', function () {
    $this->post(route('payments.store'), [
      'patient_id'  => 99999,
      'amount'      => 100.00,
      'description' => 'Test',
    ])->assertSessionHasErrors('patient_id');
  });

  it('rejects inactive patient', function () {
    $patient = Patient::factory()->inactive()->create();

    $this->post(route('payments.store'), [
      'patient_id'  => $patient->id,
      'amount'      => 100.00,
      'description' => 'Test',
    ])->assertSessionHasErrors('patient_id');
  });
});

// ===== UPDATE =====

describe('update', function () {

  it('updates payment with valid data', function () {
    $payment = PaymentAccount::factory()->create();

    $this->put(route('payments.update', $payment), [
      'patient_id'  => $payment->patient_id,
      'amount'      => 999.00,
      'description' => 'Updated',
    ])->assertRedirect(route('payments.index'));

    expect((float) $payment->fresh()->amount)->toBe(999.00);
  });

  it('returns 404 for non-existent payment', function () {
    $this->put(route('payments.update', 99999), [
      'patient_id'  => 1,
      'amount'      => 100.00,
      'description' => 'Test',
    ])->assertStatus(404);
  });
});

// ===== DESTROY =====

describe('destroy', function () {

  it('soft deletes a recent payment', function () {
    $payment = PaymentAccount::factory()->create();

    $this->delete(route('payments.destroy', $payment))
      ->assertRedirect(route('payments.index'))
      ->assertSessionHas('flash.type', 'success');

    expect(PaymentAccount::count())->toBe(0);
    expect(PaymentAccount::withTrashed()->count())->toBe(1);
  });

  it('rejects deletion of payment older than 24 hours', function () {
    // payment قديم (Gate قاعدة الـ 24 ساعة)
    $payment = PaymentAccount::factory()->create([
      'created_at' => now()->subHours(25),
    ]);

    $this->delete(route('payments.destroy', $payment))
      ->assertRedirect(route('payments.index'))
      ->assertSessionHas('flash.type', 'error');

    // السجل لا يزال موجوداً
    expect(PaymentAccount::count())->toBe(1);
  });

  it('returns 404 for non-existent payment', function () {
    $this->delete(route('payments.destroy', 99999))
      ->assertStatus(404);
  });
});

// ===== RATE LIMITING =====

describe('rate limiting', function () {

  it('throttles after 3 requests per minute', function () {
    $patient = Patient::factory()->create();

    // 3 طلب ناجح
    foreach (range(1, 3) as $i) {
      $this->post(route('payments.store'), [
        'patient_id'  => $patient->id,
        'amount'      => 10.00,
        'description' => "Request {$i}",
      ])->assertStatus(302); // redirect = نجاح
    }

    $this->post(route('payments.store'), [
      'patient_id'  => $patient->id,
      'amount'      => 10.00,
      'description' => 'Over limit',
    ])->assertStatus(429); // Too Many Requests
  });
});


describe('store', function () {

  it('creates payment with valid data', function () {

    $patient = Patient::factory()->create();

    $this->post(route('payments.store'), [

      'patient_id' => $patient->id,
      'amount' => 40.00,
      'description' => 'Test Descrition'

    ])->assertRedirect(route('payments.index'));
    expect(PaymentAccount::count())->toBe(1);
  });

  it('creates fund account entry (CREDIT)', function () {

    $patient = Patient::factory()->create();

    $this->post(route('payments.store', [
      'patient_id' => $patient->id,
      'amount' => 40.00,
      'description' => 'Test Descrition'
    ]));

    $fund = FundAccount::first();

    expect($fund)->not->toBeNull()
      ->and((float) $fund->credit)->toBe(40.00)
      ->and((float) $fund->debit)->toBe(0.00);
  });

  it('creates fund account entry (DEBIT)', function () {

    $patient = Patient::factory()->create();

    $this->post(route('payments.store', [
      'patient_id'  => $patient->id,
      'amount'      => 750.00,
      'description' => 'Test',
    ]));

    $pa = PatientAccount::first();

    expect($pa)->not->toBeNull()
      ->and((float) $pa->debit)->toBe(750.00)
      ->and((float) $pa->credit)->toBe(0.00);
  });
}); # End descripe (store)
