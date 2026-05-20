<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Group extends Model
{
    use HasFactory, SoftDeletes;
    protected $gaurded = [];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax_percent' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function services() : BelongsToMany {

        return $this->belongsToMany(Service::class)
        ->withPivot('quantity', 'unit_price')
        ->withTimestamps();

    } 

}
