<?php


namespace App\Repositories;

use App\Http\Requests\StoreReceiptRequest;
use App\Interfaces\Finance\IReceipt;
use App\Models\Patients;
use App\Models\ReceiptAccount;
use App\Services\ReceiptService;
use Illuminate\Http\RedirectResponse as HttpRedirectResponse;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Override;
use Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ReceiptReopsitory implements IReceipt
{


    public function __construct(private readonly ReceiptService $receipt_service) {}

    #[Override]
    public function index(): InertiaResponse
    {
        $receipt = ReceiptAccount::with('patient:id,name')
            ->select('id', 'date', 'patient_id', 'debit', 'description', 'created_at')
            ->latest()
            ->paginate(15)
            ->through(fn(ReceiptAccount $r) => [
                'id'          => $r->id,
                'date'        => $r->date->format('Y-m-d'),
                'patient'     => $r->patient?->name,
                'patient_id'  => $r->patient_id,
                'debit'       => $r->debit,
                'description' => $r->description,
                'created_at'  => $r->created_at,
                'urls' => [
                    'update'  => route('receipt.update',  $r->id),
                    'destroy' => route('receipt.destroy', $r->id),
                ],
            ]);
        return Inertia::render('Receipts/Index', [
            'store_url' => route('receipt.store'),
            'receipts' => $receipt,
            'patients' => Patients::where('is_active', '=', true, true)
                ->select('id', 'name')
                ->orderBy('name')
                ->get(),
        ]);
    }

    #[Override]
    public function store(StoreReceiptRequest $request): RedirectResponse
    {

        $this->receipt_service->store($request->validated());
        return redirect()->route('receipt.index')->with('flash', [
            'type'    => 'success',
            'message' => 'Receipt created successfully',
        ]);
    }

    #[Override]
    public function update(StoreReceiptRequest $request, ReceiptAccount $receipt): RedirectResponse
    {
        $this->receipt_service->update($receipt, $request->validated());

        return redirect()->route('receipt.index')->with('flash', [
            'type'    => 'success',
            'message' => 'Receipt updated successfully',
        ]);
    }

    public function destroy(ReceiptAccount $receipt): RedirectResponse
    {
        try {
            $this->receipt_service->destroy($receipt);

            return redirect()->route('receipt.index')->with('flash', [
                'type'    => 'success',
                'message' => 'Receipt deleted successfully',
            ]);
        } catch (\Exception $e) {
            return redirect()->route('receipt.index')->with('flash', [
                'type'    => 'error',
                'message' => 'Cannot delete this receipt',
            ]);
        }
    }
}
