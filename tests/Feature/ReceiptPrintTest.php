<?php

use App\Models\Admin;
use App\Models\Patients;
use App\Models\PrintLog;
use App\Models\ReceiptAccount;
use App\Services\PrintService;
use Illuminate\Support\Facades\URL;

beforeEach(function () {
    $this->admin   = Admin::factory()->create();
    $this->actingAs($this->admin, 'admins');
    $this->service = app(PrintService::class);
});

describe('Receipt show (print)', function () {

    it('returns 200 with valid signed URL', function () {
        $receipt = ReceiptAccount::factory()->create();
        $url     = $this->service->generateSigneURL($receipt, 'Receipt.show');

        $this->get($url)->assertStatus(200);
    });

    it('renders correct Inertia component', function () {
        $receipt = ReceiptAccount::factory()->create();
        $url     = $this->service->generateSigneURL($receipt, 'Receipt.show');

        $this->get($url)->assertInertia(
            fn($page) =>
            $page->component('Dashboard/Finance/Receipts/Print', false)
                ->has('receipt')
                ->has('receipt.debit')
                ->has('print_count')
        );
    });

    it('returns correct receipt data', function () {
        $patient = Patients::factory()->create(['name' => 'Sara Ibrahim']);
        $receipt = ReceiptAccount::factory()->create([
            'patient_id'  => $patient->id,
            'debit'       => 300.00,
            'description' => 'Payment received',
        ]);

        $url = $this->service->generateSigneURL($receipt, 'Receipt.show');

        $this->get($url)->assertInertia(
            fn($page) =>
            $page->where('receipt.patient', 'Sara Ibrahim')
                ->where('receipt.debit', '300.00')
        );
    });

    it('creates PrintLog on view', function () {
        $receipt = ReceiptAccount::factory()->create();
        $url     = $this->service->generateSigneURL($receipt, 'Receipt.show');

        $this->get($url);

        $log = PrintLog::first();
        expect($log->printable_type)->toBe(ReceiptAccount::class)
            ->and($log->printable_id)->toBe($receipt->id)
            ->and($log->action)->toBe('view');
    });

    it('returns 403 with expired URL', function () {
        $receipt = ReceiptAccount::factory()->create();

        $expiredUrl = URL::temporarySignedRoute(
            'Receipt.show',
            now()->subMinutes(5),
            ['receipt' => $receipt->id]
        );

        $this->get($expiredUrl)->assertStatus(403);
    });
});

describe('Receipt download (PDF)', function () {

    it('returns print view for download', function () {
        $receipt = ReceiptAccount::factory()->create();
        $url     = $this->service->generateSigneURL($receipt, 'Receipt.download');

        $this->get($url)
            ->assertStatus(200);
    });

    it('logs download action', function () {
        $receipt = ReceiptAccount::factory()->create();
        $url     = $this->service->generateSigneURL($receipt, 'Receipt.download');

        $this->get($url);

        expect(PrintLog::first()->action)->toBe('download');
    });
});
