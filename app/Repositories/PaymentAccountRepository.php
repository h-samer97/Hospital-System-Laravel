<?php


namespace App\Repositories;

use App\Http\Requests\StorePaymentRequest;
use App\Interfaces\IPatients;
use App\Interfaces\IPayment;
use App\Models\Patients;
use App\Models\PaymentAccount;
use App\Services\PaymentService;
use App\Suppoet\BinarySearch;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Override;

class PaymentAccountRepository implements IPayment
{


  private const PATIENTS_CACHE_TTL = 1800; // 30 minute
  private const PATIENTS_CACHE_KEY = 'patients:active:sorted';

  public function __construct(private readonly PaymentService $payment_service) {}

  #[Override]
  public function index(): InertiaResponse
  {

    $payment = PaymentAccount::with('patient:id,name')
      ->select('id', 'date', 'patient_id', 'amount', 'description', 'created_at')
      ->latest()
      ->paginate(15)
      ->through(fn(PaymentAccount $pay) => [
        'id' => $pay->id,
        'date' => $pay->date->format('Y-m-d'),
        'patient' => $pay->patient?->name,
        'patient_id'  => $pay->patient_id,
        'amount'      => $pay->amount,
        'description' => $pay->description,
        'created_at'  => $pay->created_at,
        'urls'  => [
          'update' => route('payments.update', $pay->id),
          'destroy' => route('payments.destroy', $pay->id),
        ]
      ]);

    return Inertia::render('payments/index', [
      'payments' => $payment,
      'store_url' => route('payments.store'),
      'patients' => $this->getCachedPatients()
    ]);
  }


  #[Override]
  public function store(StorePaymentRequest $request): RedirectResponse
  {
    $this->payment_service->store(
      $request->validated(),
      Auth::guard('admins')->id()
    );
    return \redirect()->route('payments.index')->with('flash', [
      'type' => 'success',
      'message' => 'Payment Created Successfully'
    ]);
  }

  #[Override]
  public function update(StorePaymentRequest $request, PaymentAccount $payment): RedirectResponse
  {
    $this->payment_service->update(
      $payment,
      $request->validated(),
      Auth::guard('admins')->id(),
    );

    return \redirect()->route('payments.index')->with('flash', [
      'type' => 'success',
      'message' => 'Payment Updated Successfully'
    ]);
  }

  #[Override]
  public function destroy(PaymentAccount $payment): RedirectResponse
  {
    if (!Auth::guard('admins')->user()->can('payment.delete', $payment)) {
      return redirect()->route('payments.index')->with('flash', [
        'type'    => 'error',
        'message' => 'Cannot delete payments older than 24 hours',
      ]);
    }

    $this->payment_service->destroy(
      $payment,
      Auth::guard('admins')->id()
    );

    return \redirect()->route('payments.index')->with('flash', [
      'type' => 'success',
      'message' => 'Payment Deleted Successfully'
    ]);
  }

  private function getCachedPatients(): array
  {
    return Cache::remember(self::PATIENTS_CACHE_KEY, self::PATIENTS_CACHE_TTL, function () {
      return Patients::where('is_active', true)
        ->select('id', 'name')
        ->orderBy('name', 'asc')
        ->get()
        ->toArray();
    });
  }

  private function findPatientByExactName(string $name): ?array
  {

    return BinarySearch::search($this->getCachedPatients(), $name, 'name');
  }
}
