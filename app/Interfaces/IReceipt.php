<?php

namespace App\Interfaces\Finance;

use App\Http\Requests\StoreReceiptRequest;
use App\Models\ReceiptAccount;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;

interface IReceipt
{
    public function index(): Response;
    public function store(StoreReceiptRequest $request): RedirectResponse;
    public function update(StoreReceiptRequest $request, ReceiptAccount $receipt): RedirectResponse;
    public function destroy(ReceiptAccount $receipt): RedirectResponse;

    public function show(ReceiptAccount $receipt): RedirectResponse;
    public function download(ReceiptAccount $receipt): RedirectResponse;
}
