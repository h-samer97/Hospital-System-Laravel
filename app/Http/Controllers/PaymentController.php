<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentRequest;
use App\Interfaces\IPayment;
use App\Models\PaymentAccount;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response as HttpResponse;
use Inertia\Response;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class PaymentController extends Controller implements IPayment
{
  use AuthorizesRequests;

  public function __construct(private readonly IPayment $paymentRepository) {}

  public function index(): Response
  {
    $this->authorize('payment.viewany');
    return $this->paymentRepository->index();
  }

  public function store(StorePaymentRequest $request): RedirectResponse
  {
    return $this->paymentRepository->store($request);
  }

  public function update(StorePaymentRequest $request, PaymentAccount $payment): RedirectResponse
  {
    return $this->paymentRepository->update($request, $payment);
  }

  public function destroy(PaymentAccount $payment): RedirectResponse
  {
    return $this->paymentRepository->destroy($payment);
  }

  public function show(PaymentAccount $payment): Response
  {
    return $this->paymentRepository->show($payment);
  }

  public function download(PaymentAccount $payment): HttpResponse
  {
    return $this->paymentRepository->download($payment);
  }
}
