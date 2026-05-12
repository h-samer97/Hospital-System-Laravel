<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Section extends Model
{
    protected $fillable = ['name', 'is_active'];
    use SoftDeletes;
    use HasFactory;

    protected $casts = [
        'is_active' => 'boolean',
    ];

}
