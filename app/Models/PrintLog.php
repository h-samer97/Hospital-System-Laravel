<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class PrintLog extends Model
{
    public $timestamps = true;
    const UPDATED_AT = null;

    protected $fillable = [
    'printable_id',
    'printable_type',
    'admin_id',
    'action',
    'ip_address',
    'user_agent',
];


public function printable() : MorphTo {
    return $this->morphTo();
}

public function admin() : BelongsTo {
    return $this->belongsTo(Admin::class);
}

}
