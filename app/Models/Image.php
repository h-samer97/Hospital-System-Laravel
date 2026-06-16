<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Image extends Model
{
    use HasFactory;
    protected $fillable = [
        'filename',
        'imageable_type',
        'imageable_id',
    ];

    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }

    /** مسار الصورة الكامل */
    public function getUrlAttribute(): string
    {
        return asset('storage/doctors/' . $this->filename);
    }
}
