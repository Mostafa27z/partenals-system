<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestChangeChip extends Model
{
    protected $fillable = [
        'request_id',
        'change_type',
        'old_serial',
        'new_serial',
        'request_date',
        'full_name',
        'national_id',
        'comment',
    ];

    public function request()
    {
        return $this->belongsTo(Request::class);
    }
}
