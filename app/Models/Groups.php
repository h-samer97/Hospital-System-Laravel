<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Groups extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'notes',
        'total_before_discount',
        'discount_value',
        'total_after_discount',
        'tax_rate',
        'total_with_tax',
    ];

    public function service_group()
    {
        return $this->belongsToMany(SingleServices::class, 'pivot_services_groups', 'group_id', 'service_id')
            ->withPivot('quantity');
    }
}
