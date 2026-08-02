<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Patients as Patient;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Support\LogOptions;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class ReceiptAccount extends Model
{

    use HasFactory;

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

    public function FundAccount(): HasOne
    {
        return $this->hasOne(FundAccounts::class, 'receipt_id');
    }
    public function patientAccount(): HasOne
    {
        return $this->hasOne(PatientAccounts::class, 'receipt_id');
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['patient_id', 'debit', 'description'])
            ->logOnlyDirty()
            ->useLogName('receipt');
    }

    public function printLogs(): MorphMany
    {
        return $this->morphMany(PrintLog::class, 'printable');
    }
}
