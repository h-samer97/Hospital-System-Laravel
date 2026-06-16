<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Patient;

class ReceiptAccount extends Model
{
    protected $fillable = [
         'date',
        'patient_id',
        'debit',
        'description',
    ];

    protected $casts = [
        'date' => 'date:Y-m-d',
        'debit' => 'decimal:2'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function FundAccount() : HasOne {
        return $this->hasOne(FundAccounts::class, 'receipt_id');
    }
    public function patientAccount() : HasOne {

        return $this->hasOne(PatientAccounts::class, 'receipt_id');

    }

}
