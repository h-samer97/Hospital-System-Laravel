<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Section;

class Doctor extends Model
{
    use HasFactory;

    protected $guarded = [];

    # Doctor has one image :)
    public function images(){
        return $this->morphOne(Image::class,'imageable');
    }
    
    # Doctor Has One Section

    public function section() {
        return $this->belongsTo(Section::class);
    }
}
