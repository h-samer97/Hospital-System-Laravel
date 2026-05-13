<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Section;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use App\Models\Image;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Doctor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'appointments',
        'email',
        'email_verified_at',
        'password',
        'phone',
        'price',
        'is_active',
        'section_id',
    ];

    protected $hidden = ['password'];

    // Convert Types 
    protected $casts = [
        'email_verified_at' => 'datetime',
        'price'             => 'decimal:2',
        'is_active'         => 'boolean',
    ];

    public function section() : BelongsTo
    {
        return $this->belongsTo(Section::class);
    }
    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

}
