<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    use SoftDeletes;
    use HasFactory;

     protected $fillable = ['name',
        'description',
        'price',
        'is_active'];
   
    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    public function groups() : belongsToMany {

        return $this->belongsToMany(Group::class);

    }

}
