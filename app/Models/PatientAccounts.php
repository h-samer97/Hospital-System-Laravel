<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\SingleInvoices as SingleInvoice;
use App\Models\Patients as Patient;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PatientAccounts extends Model
{
    use HasFactory;
    protected $fillable = [
        'date',
        'single_invoice_id',
        'patient_id',
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

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}
