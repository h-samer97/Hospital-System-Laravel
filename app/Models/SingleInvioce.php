<?php

namespace App\Models;

use Database\Factories\SingleInvoiceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SingleInvioce extends Model
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

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function service()
    {
        return $this->belongsTo(SingleServices::class, 'service_id');
    }

    protected static function newFactory()
    {
        return SingleInvoiceFactory::new();
    }
}
