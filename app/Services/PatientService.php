<?php

namespace App\Services;

use App\Models\PatientAccounts;
use App\Models\Patients;
use App\Models\PaymentAccount;
use App\Models\ReceiptAccount;
use App\Models\SingleInvoices;
use Cache;
use Illuminate\Database\Eloquent\Model;
use Pest\TestCaseMethodFilters\FlakyTestCaseFilter;

class PatientService
{

    private const Cache_TTL = 300; # 15:00 minute

    public function __construct(private readonly PrintService $pService) {}


    public function financialSummary(Patients $patient): array
    {

        return Cache::remember(
            "Patient:{$patient->id}:financial_summary",
            self::Cache_TTL,
            function () use ($patient) {

                $totalInvoices = SingleInvoices::where('patient_id', $patient->id)->sum('total_with_tax');
                $totalReceipt = ReceiptAccount::where('patient_id', $patient->id)->sum('debit');
                $totalPayments = PaymentAccount::where('patient_id', $patient->id)->sum('amount');

                $debitTotal = PatientAccounts::where('patient_id', $patient->id)->sum('debit');
                $creditTotal = PatientAccounts::where('patient_id', $patient->id)->sum('credit');
                $balance    = (float) $debitTotal - (float) $creditTotal;

                return [
                    'total_invoiced' => \round((float) $totalInvoices, 2),
                    'total_receipt' => \round((float) $totalReceipt, 2),
                    'total_payments' => \round((float) $totalPayments, 2),
                    'balance' => \round((float) $balance, 2),
                    'debit_total' => \round((float) $debitTotal, 2),
                    'credit_total' => \round((float) $creditTotal, 2),
                    'balance_total' => $balance < 0 ? \round((float) $balance * -1, 2) : \round((float) $balance, 2),
                    'is_debtor' => $balance > 0 ? true : false,
                ];
            }
        );
    }
    public function ledger(Patients $patient): array
    {

        return Cache::remember(
            "patient:{$patient}:ledger",
            self::Cache_TTL,
            function () use ($patient) {

                $account = PatientAccounts::with([
                    'invoice:id,service_id,total_with_tax',
                    'invoice.service_id:id,name',
                    'receipt:id,description',
                    'payment:id,description'
                ])
                    ->where('patient_id', $patient->id)
                    ->orderBy('date')
                    ->orderBy('id')
                    ->get();

                $runningBalance = 0.00;

                return $account->map(function (PatientAccounts $PA) use (&$runningBalance) {

                    $description = match (true) {

                        $PA->invoice !== null => $PA->invoice?->service?->name ?? 'Service Invoice',
                        $PA->receipt_id !== null => $PA->receipt?->description ?? "Receipt",
                        $PA->payment_id !== null => $PA->payment?->description ?? "Payment",
                        default => "N/A"
                    };

                    $entryType = match (true) {

                        $PA->invoice?->invoice_id !== null => 'invoice',
                        $PA->receipt_id !== null => 'receipt',
                        $PA->payment_id !== null => 'payment',
                        default => 'N/A'
                    };

                    $runningBalance += (float) ($PA->debit - $PA->credit);

                    return [
                        'id' => $PA->id,
                        'date' => $PA->date->format('Y-n-j'),
                        'description' => $description,
                        'type' => $entryType,
                        'debit' => \round((float) $PA->debit, 2),
                        'credit' => round((float) $PA->credit, 2),
                        'running_balance' => \round((float) $runningBalance, 2),
                        'is_debtor' => $runningBalance > 0 ? true : false,
                    ];
                })->toArray();
            }
        );
    }

    public function patientProfile(Patients $patient): array
    {
        return [
            'id'           => $patient->id,
            'name'         => $patient->name,
            'email'        => $patient->email,
            'phone'        => $patient->phone,
            'date_birth'   => $patient->date_birth->format('Y-m-d'),
            'age'          => $patient->age,
            'gender'       => $patient->gender,
            'gender_label' => $patient->gender_label,
            'blood_group'  => $patient->blood_group,
            'address'      => $patient->address,
            'is_active'    => $patient->is_active,
            'member_since' => $patient->created_at->format('Y-m-d'),
        ];
    }

    public function generateSignedUrl(Model $model): string
    {

        $routeMap = [
            SingleInvoices::class => 'invoices.show',
            Receipt::class        => 'receipt.show',
            PaymentAccount::class => 'payments.show'
        ];

        $route = $routeMap[$model::class] ?? null;
        if (!$route) return '#';
        return $this->pService->generateSigneURL($model, $route);
    }
}
