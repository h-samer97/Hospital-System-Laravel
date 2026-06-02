<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ambulances extends Model
{

    use HasFactory;

    protected $fillable = [
        'car_number',
        'car_model',
        'car_year_made',
        'driver_name',
        'driver_license_number',
        'is_available',
        'status',
        'notes',
        'car_type',
        'driver_phone',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'status' => 'boolean',
        'car_year_made' => 'integer',
    ];
}
