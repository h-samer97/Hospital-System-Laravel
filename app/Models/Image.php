<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $guarded = [];

    # Image belongs to a doctor or a section or a patient or a nurse or an admin

    public function imageable(){
        return $this->morphTo();
    }
}
