<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Group extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $guarded = [];

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
