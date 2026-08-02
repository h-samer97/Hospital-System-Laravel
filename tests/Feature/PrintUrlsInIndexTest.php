<?php

use App\Models\Admin;
use App\Models\PaymentAccount;
use App\Models\ReceiptAccount;
use App\Models\SingleInvoices;

beforeEach(function () {
    $this->admin = Admin::factory()->create();
    $this->actingAs($this->admin, 'admins');
});

// يتأكد أن روابط الطباعة تُرسَل مع بيانات الـ Index

it('payment index includes signed print URLs', function () {
    PaymentAccount::factory(3)->create();

    $this->get(route('payments.index'))->assertInertia(fn ($page) =>
        $page->has('payments.data.0.urls.print')
             ->has('payments.data.0.urls.download')
    );
});

it('payment print URL contains valid signature', function () {
    PaymentAccount::factory()->create();

    $this->get(route('payments.index'))->assertInertia(fn ($page) =>
        $page->where('payments.data.0.urls.print', fn($url) => 
            str($url)->contains('signature=') && str($url)->contains('expires=')
        )
    );
});

it('receipt index includes signed print URLs', function () {
    ReceiptAccount::factory(2)->create();

    $this->get(route('receipt.index'))->assertInertia(fn ($page) =>
        $page->has('receipts.data.0.urls.print')
             ->has('receipts.data.0.urls.download')
    );
});

it('single invoice index includes signed print URLs', function () {
    SingleInvoices::factory(2)->create();

    $this->get(route('single_invoices.index'))->assertInertia(fn ($page) =>
        $page->has('invoices.0.urls.print')
             ->has('invoices.0.urls.download')
    );
});

it('print URL expires and becomes invalid', function () {
    $payment = PaymentAccount::factory()->create();

    // نولّد URL منتهية
    $expiredUrl = \Illuminate\Support\Facades\URL::temporarySignedRoute(
        'payments.show',
        now()->subSecond(),
        ['payment' => $payment->id]
    );

    $this->get($expiredUrl)->assertStatus(403);
});