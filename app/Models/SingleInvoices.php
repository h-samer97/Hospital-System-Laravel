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
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Override;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class SingleInvoices extends Model
{
    use HasFactory, LogsActivity;


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
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['patient_id', 'doctor_id', 'service_id', 'total_with_tax', 'type'])
        ->logOnlyDirty()
        ->dontLogEmptyChanges()
        ->useLogName('single_invoice');
    }

    public function printLogs() : MorphMany {
        return $this->morphMany(PrintLog::class, 'printable');
    }

    public function getPrintCountAttribute() : int {
        return $this->printLogs()->count();
    }
    
    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'cash'     => 'Cash',
            'deferred' => 'Deferred',
            default    => 'Unknown',
        };
    }
}
