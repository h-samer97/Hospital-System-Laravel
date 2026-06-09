<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Patients;
use App\Models\Doctor;
use App\Models\Section;
use App\Models\Service;
use App\Models\FundAccounts;
use App\Models\PatientAccounts;

class SingleInvoices extends Model
{
    use HasFactory;
    protected $fillable = [
        'invoice_date',
        'patient_id',
        'doctor_id',
        'section_id',
        'service_id',
        'price',
        'discount_value',
        'tax_rate',
        'tax_value',
        'total_with_tax',
        'type',
    ];


    protected $casts = [

        'invoice_date' => 'date:Y-m-d',
        'price'          => 'decimal:2',
        'discount_value' => 'decimal:2',
        'tax_rate'       => 'decimal:2',
        'tax_value'      => 'decimal:2',
        'total_with_tax' => 'decimal:2',

    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patients::class, 'patient_id', 'id');
    }
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'doctor_id', 'id');
    }
    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class, 'section_id', 'id');
    }
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id', 'id');
    }
    public function fundAccount(): HasOne
    {
        return $this->hasOne(FundAccounts::class, 'single_invoice_id', 'id');
    }
    public function patientAccount(): HasOne
    {
        return $this->hasOne(PatientAccounts::class, 'single_invoice_id', 'id');
    }
    public function getTypeLabelAttr(): string
    {
        return match ($this->type) {
            'cash'     => 'Cash',
            'deferred' => 'Deferred',
            default    => 'Unknown',
        };
    }
}
