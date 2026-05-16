<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Doctor;

class Section extends Model
{
    protected $fillable = ['name', 'is_active'];
    use SoftDeletes;
    use HasFactory;

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function doctors() : HasMany {
        return $this->hasMany(Doctor::class);
    }
    public function active($query) : Builder {
        return $query->where('is_active', true);
    }

}
