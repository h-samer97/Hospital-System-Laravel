<?php

namespace App\Interfaces;

use App\Http\Requests\StorePaymentRequest;
use App\Models\PaymentAccount;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;

interface IPayment
{
    public function index(): Response;
    public function store(StorePaymentRequest $request): RedirectResponse;
    public function update(StorePaymentRequest $request, PaymentAccount $payment): RedirectResponse;
    public function destroy(PaymentAccount $payment): RedirectResponse;
    // private function getCachedPatients() : array;
}