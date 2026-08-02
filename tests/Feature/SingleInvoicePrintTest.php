<?php

use App\Models\Admin;
use App\Models\Doctor;
use App\Models\Patients;
use App\Models\PrintLog;
use App\Models\Section;
use App\Models\Service;
use App\Models\SingleInvoices;
use App\Services\PrintService;
use Illuminate\Support\Facades\URL;

beforeEach(function () {
    $this->admin   = Admin::factory()->create();
    $this->actingAs($this->admin, 'admins');
    $this->service = app(PrintService::class);
});

describe('SingleInvoice show (print)', function () {

    it('returns 200 with valid signed URL', function () {
        $invoice = SingleInvoices::factory()->create();
        $url     = $this->service->generateSigneURL($invoice, 'single_invoices.show');

        $this->get($url)->assertStatus(200);
    });

    it('renders correct Inertia component', function () {
        $invoice = SingleInvoices::factory()->create();
        $url     = $this->service->generateSigneURL($invoice, 'single_invoices.show');

        $this->get($url)->assertInertia(fn ($page) =>
            $page->component('Dashboard/Invoices/SingleInvoices/Print', false)
                 ->has('invoice')
                 ->has('invoice.total_with_tax')
                 ->has('invoice.type_label')
                 ->has('print_count')
        );
    });

    it('returns all required invoice fields', function () {
        $patient = Patients::factory()->create(['name' => 'Mohamed Hassan']);
        $section = Section::factory()->create(['name' => 'Emergency']);
        $doctor  = Doctor::factory()->create([
            'name'       => 'Dr. Ahmed',
            'section_id' => $section->id,
        ]);
        $service = Service::factory()->create(['name' => 'X-Ray']);

        $invoice = SingleInvoices::factory()->cash()->create([
            'patient_id'     => $patient->id,
            'doctor_id'      => $doctor->id,
            'section_id'     => $section->id,
            'service_id'     => $service->id,
            'price'          => 500.00,
            'discount_value' => 50.00,
            'tax_rate'       => 17.00,
            'tax_value'      => 76.50,
            'total_with_tax' => 526.50,
        ]);

        $url = $this->service->generateSigneURL($invoice, 'single_invoices.show');

        $this->get($url)->assertInertia(fn ($page) =>
            $page->where('invoice.patient', 'Mohamed Hassan')
                 ->where('invoice.doctor', 'Dr. Ahmed')
                 ->where('invoice.section', 'Emergency')
                 ->where('invoice.service', 'X-Ray')
                 ->where('invoice.type', 'cash')
                 ->where('invoice.type_label', 'Cash')
                 ->where('invoice.total_with_tax', '526.50')
        );
    });

    it('shows deferred invoice correctly', function () {
        $invoice = SingleInvoices::factory()->deferred()->create();
        $url     = $this->service->generateSigneURL($invoice, 'single_invoices.show');

        $this->get($url)->assertInertia(fn ($page) =>
            $page->where('invoice.type', 'deferred')
                 ->where('invoice.type_label', 'Deferred')
        );
    });

    it('creates PrintLog on view', function () {
        $invoice = SingleInvoices::factory()->create();
        $url     = $this->service->generateSigneURL($invoice, 'single_invoices.show');

        $this->get($url);

        $log = PrintLog::first();
        expect($log->printable_type)->toBe(SingleInvoices::class)
            ->and($log->printable_id)->toBe($invoice->id);
    });

    it('shows correct print_count', function () {
        $invoice = SingleInvoices::factory()->create();
        $url     = $this->service->generateSigneURL($invoice, 'single_invoices.show');

        $this->get($url)->assertInertia(fn ($page) =>
            $page->where('print_count', 1)
        );
    });

    it('returns 403 for unsigned URL', function () {
        $invoice = SingleInvoices::factory()->create();

        $this->get(route('single_invoices.show', $invoice))
             ->assertStatus(403);
    });

    it('returns 403 for expired URL', function () {
        $invoice = SingleInvoices::factory()->create();

        $expiredUrl = URL::temporarySignedRoute(
            'single_invoices.show',
            now()->subHour(),
            ['invoice' => $invoice->id]
        );

        $this->get($expiredUrl)->assertStatus(403);
    });

    it('returns 404 for non-existent invoice', function () {
        $url = URL::temporarySignedRoute(
            'single_invoices.show',
            now()->addMinutes(30),
            ['invoice' => 99999]
        );

        $this->get($url)->assertStatus(404);
    });

});

describe('SingleInvoice download (PDF)', function () {

    it('returns print view for download', function () {
        $invoice = SingleInvoices::factory()->create();
        $url     = $this->service->generateSigneURL($invoice, 'single_invoices.download');

        $this->get($url)
             ->assertStatus(200);
    });

    it('logs download separately from view', function () {
        $invoice = SingleInvoices::factory()->create();

        $viewUrl     = $this->service->generateSigneURL($invoice, 'single_invoices.show');
        $downloadUrl = $this->service->generateSigneURL($invoice, 'single_invoices.download');

        $this->get($viewUrl);
        $this->get($downloadUrl);

        expect(PrintLog::where('action', 'view')->count())->toBe(1)
            ->and(PrintLog::where('action', 'download')->count())->toBe(1);
    });

});