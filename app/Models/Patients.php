<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patients extends Model
{
    protected $fillable = [
        'name', 'email', 'password', 'birth_date', 'phone', 'gender', 'blood_group', 'address'
    ];

        public function casts() : array {
    
            return [
    
            'birth_date' => 'date: Y-m-d',
            'password' => 'hashed',
            'gender' => 'integer',
            
            ];
    
        }
}
