<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patients extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'birth_date',
        'phone',
        'gender',
        'blood_group',
        'address',
        'is_active',
    ];

    public function casts(): array
    {

        return [

            'birth_date' => 'date: Y-m-d',
            'password' => 'hashed',
            'gender' => 'integer',

        ];
    }
}
