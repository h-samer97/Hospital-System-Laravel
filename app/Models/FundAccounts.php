<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\SingleInvoices as SingleInvoice;

class FundAccounts extends Model
{
    use HasFactory;
    protected $fillable = [
        'date',
        'single_invoice_id',
        'debit',
        'credit',
        'notes',
    ];

    protected $casts = [
        'date'   => 'date',
        'debit'  => 'decimal:2',
        'credit' => 'decimal:2',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(SingleInvoice::class, 'single_invoice_id');
    }

    public function receipt() : BelongsTo {
        return $this->belongsTo(ReceiptAccount::class, 'receipt_id');
    }
    
}
