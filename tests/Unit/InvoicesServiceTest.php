<?php

use App\Services\InvoiceService;

// ===== اختبار حسابات الفواتير =====

beforeEach(function () {
    $this->service = app(InvoiceService::class);
});

it('calculates subtotal correctly', function () {
    $result = $this->service->calculate(
        price: 1000.00, discount: 100.00, taxRate: 0
    );

    expect($result['subtotal'])->toBe(900.00);
});

it('calculates tax value correctly', function () {
    $result = $this->service->calculate(
        price: 1000.00, discount: 0, taxRate: 17.00
    );

    // 1000 × 17% = 170
    expect($result['tax_value'])->toBe(170.00);
});

it('calculates total with tax and discount', function () {
    $result = $this->service->calculate(
        price: 1000.00, discount: 100.00, taxRate: 17.00
    );

    // subtotal = 900
    // tax = 900 × 17% = 153
    // total = 900 + 153 = 1053
    expect($result['total'])->toBe(1053.00);
});

it('handles zero discount', function () {
    $result = $this->service->calculate(
        price: 500.00, discount: 0, taxRate: 10.00
    );

    expect($result['subtotal'])->toBe(500.00)
        ->and($result['tax_value'])->toBe(50.00)
        ->and($result['total'])->toBe(550.00);
});

it('handles zero tax rate', function () {
    $result = $this->service->calculate(
        price: 500.00, discount: 50.00, taxRate: 0
    );

    expect($result['tax_value'])->toBe(0.00)
        ->and($result['total'])->toBe(450.00);
});

it('rounds to 2 decimal places', function () {
    $result = $this->service->calculate(
        price: 100.00, discount: 0, taxRate: 17.00
    );

    // 100 × 17% = 17.00 (نظيف)
    expect($result['tax_value'])->toBe(17.00);

    $result2 = $this->service->calculate(
        price: 333.00, discount: 0, taxRate: 17.00
    );

    // 333 × 17% = 56.61
    expect($result2['tax_value'])->toBe(56.61);
});