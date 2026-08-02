<?php


namespace App\Repositories;

use App\Http\Requests\StorePaymentRequest;
use App\Interfaces\IPayment;
use App\Models\Patients;
use App\Models\PaymentAccount;
use App\Services\PaymentService;
use App\Services\PrintService;
use App\Suppoet\BinarySearch;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class PaymentAccountRepository implements IPayment
{


  private const PATIENTS_CACHE_TTL = 1800; // 30 minute
  private const PATIENTS_CACHE_KEY = 'patients:active:sorted';

  public function __construct(
    private readonly PaymentService $payment_service,
    private readonly PrintService $print
  ) {}

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
          'print'    => $this->print->generateSigneURL($pay, 'payments.show'),
          'download' => $this->print->generateSigneURL($pay, 'payments.download'),
        ]
      ]);

    return Inertia::render('payments/index', [
      'payments' => $payment,
      'store_url' => route('payments.store'),
      'patients' => $this->getCachedPatients()
    ]);
  }


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

  public function show(PaymentAccount $payment): InertiaResponse
  {
    $payment->load('patient:id,name,phone,address');
    $this->print->logPrint($payment, request(), 'view');

    return Inertia::render('Payments/Print', [
      'payment'     => [
        'id'          => $payment->id,
        'date'        => $payment->date->format('Y-m-d'),
        'patient'     => $payment->patient?->name,
        'phone'       => $payment->patient?->phone,
        'address'     => $payment->patient?->address,
        'amount'      => $payment->amount,
        'description' => $payment->description,
        'created_at'  => $payment->created_at->format('Y-m-d H:i'),
      ],
      'print_count' => $payment->printLogs()->count(),
    ]);
  }

  public function download(PaymentAccount $payment): Response
  {
    $payment->load('patient:id,name,phone,address');
    $this->print->logPrint($payment, request(), 'download');

    return Inertia::render('Payments/Print', [
      'payment' => [
        'id'          => $payment->id,
        'date'        => $payment->date->format('Y-m-d'),
        'patient'     => $payment->patient?->name,
        'phone'       => $payment->patient?->phone,
        'address'     => $payment->patient?->address,
        'amount'      => $payment->amount,
        'description' => $payment->description,
        'created_at'  => $payment->created_at->format('Y-m-d H:i'),
      ],
      'print_count' => $payment->printLogs()->count(),
    ]);
  }
}
