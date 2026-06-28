<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentAccount extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'date',
        'patient_id',
        'description',
        'amount'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date'  => 'date'
    ];

    public function patient() : BelongsTo {
        return $this->belongsTo(Patients::class);
    }
    public function fundAccount() : HasOne {
        return $this->hasOne(FundAccounts::class, 'payment_id');
    }

    public function patientAccount() : HasOne {
        return $this->hasOne(PatientAccounts::class, 'payment_id');
    }

}
