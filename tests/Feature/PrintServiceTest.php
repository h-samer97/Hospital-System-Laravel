<?php

use App\Models\Admin;
use App\Models\PaymentAccount;
use App\Models\PrintLog;
use App\Models\ReceiptAccount;
use App\Services\PrintService;
use Faker\Provider\Payment;
use Spatie\Activitylog\Models\Activity;

beforeEach(function () {

  $this->service = app(PrintService::class);
  $this->admin   = Admin::factory()->create();
  $this->actingAs($this->admin, 'admins');
});

describe('generateSignedUrl', function () {


  it('generate a valid signed url', function () {
    $payment = PaymentAccount::factory()->create();
    $url     = $this->service->generateSigneURL($payment, 'payments.show');

    expect($url)->toContain('signature=')
      ->and($url)->toContain('expires=')
      ->and($url)->toContain((string) $payment->id);
  });

  it('generate a url expires in 30 minutes', function () {

    $payment = PaymentAccount::factory()->create();

    $url = $this->service->generateSigneURL($payment, 'payments.show');

    parse_str(parse_url($url, PHP_URL_QUERY), $params);

    $expire = (int) $params['expires'];

    expect($expire)->toBeGreaterThan(now()->timestamp)
      ->and($expire)->toBeLessThanOrEqual(now()->addMinute(30)->timestamp + 5);
  });

  it('generate different URLs for different Documents', function () {

    $pay1 = PaymentAccount::factory()->create();
    $pay2 = PaymentAccount::factory()->create();

    $url1 = $this->service->generateSigneURL($pay1, 'payments.show');
    $url2 = $this->service->generateSigneURL($pay2, 'payments.show');

    expect($url1)->not->toBe($url2);
  });

  it('generates invalid URL when tampered', function () {

    $payment = PaymentAccount::factory()->create();
    $url = $this->service->generateSigneURL($payment, 'payments.show');

    $url_faker = preg_replace('/signature=[^&]+/', 'signature=fakerURL', $url);

    expect(URL::hasValidSignature(request()->create($url_faker)))->toBeFalse();
  });
});

describe('logPrint', function () {

  it('creates a printLog record on VIEW', function () {

    $payment = PaymentAccount::factory()->create();
    $request = Request::create('/test', 'GET', [], [], [], [
      'REMOTE_ADDR' => '127.0.0.1',
      'HTTP_USER_AGENT' => 'TestBrowser/1.0'
    ]);

    $this->service->logPrint($payment, $request, 'view');

    expect(PrintLog::count())->toBe(1);

    $log = PrintLog::first();

    expect($log->printable_type)->toBe(PaymentAccount::class)
      ->and($log->printable_id)->toBe($payment->id)
      ->and($log->admin_id)->toBe($this->admin->id)
      ->and($log->action)->toBe('view')
      ->and($log->ip_address)->toBe('127.0.0.1');
  });


  it('creates a printing record on DOWNLOAD', function () {

    $receipt = ReceiptAccount::factory()->create();
    $request = Request::create('/test');

    $this->service->logPrint($receipt, $request, 'download');

    $log = PrintLog::first();

    expect($log->action)->toBe('download')
      ->and($log->printable_type)->toBe(ReceiptAccount::class);
  });

  it('stores user agent in print log', function () {

    $payment = PaymentAccount::factory()->create();
    $request = Request::create('/test', 'GET', [], [], [], [

      'HTTP_USER_AGENT' => 'Mozilla/5.0 TestAgent'

    ]);

    $this->service->logPrint($payment, $request, 'view');

    $log = PrintLog::first();

    expect($log->user_agent)->toBe('Mozilla/5.0 TestAgent');
  });


  it('logs spatie activity on print', function () {

    $payment = PaymentAccount::factory()->create();
    $request = Request::create('/test');

    $this->service->logPrint($payment, $request, 'view');

    $activity = Activity::first();

    expect($activity)->not->toBeNull()
      ->and($activity->causer->id)->toBe($this->admin->id)
      ->and($activity->subject->id)->toBe($payment->id);
  });


  it('return the created PrintLog Instance', function () {

    $payment = PaymentAccount::factory()->create();
    $request = Request::create('/test');

    $this->service->logPrint($payment, $request, 'view');

    $log = PrintLog::first();

    expect($log)->toBeInstanceOf(PrintLog::class)
      ->and($log->id)->not->toBeNull();
  });

  it('increments print count on document', function () {

    $payment = PaymentAccount::factory()->create();
    $request = Request::create('/test');

    expect($payment->printLogs()->count())->toBe(0);

    $this->service->logPrint($payment, $request, 'view');
    $this->service->logPrint($payment, $request, 'view');
    $this->service->logPrint($payment, $request, 'download');

    expect($payment->printLogs()->count())->toBe(3);

    $this->service->logPrint($payment, $request, 'view');
    $this->service->logPrint($payment, $request, 'download');
    $this->service->logPrint($payment, $request, 'view');

    expect($payment->printLogs()->count())->toBe(6);
  });
});
describe('PrintLog model', function () {

  it('has no updated_at column', function () {

    $payment = PaymentAccount::factory()->create();
    $log = PrintLog::create([

      'printable_type' => PaymentAccount::class,
      'printable_id'   => $payment->id,
      'admin_id'      => $this->admin->id,
      'ip_address'    => '127.0.0.1',
    ]);

    expect($log->updated_at)->toBeNull();
  });

  it('returns correct printable model via morphTo', function() {

    $payment = PaymentAccount::factory()->create();
    $log = PrintLog::create([

      'printable_type' => PaymentAccount::class,
      'printable_id'   => $payment->id,
      'admin_id'      => $this->admin->id,
      'ip_address'    => '127.0.0.1',
    ]);

    expect($log->printable)->toBeInstanceOf(PaymentAccount::class)
    ->and($log->printable->id)->toBe($payment->id);

  });


});
