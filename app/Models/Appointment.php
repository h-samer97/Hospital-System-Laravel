<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Doctor;

class Appointment extends Model
{
    protected $fillable = ['name'];


    public function doctor() : BelongsToMany {
        return $this->belongsToMany(Doctor::class);
    }

}
