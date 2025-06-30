<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestChangeDistributor extends Model
{
    protected $fillable = [
        'request_id', 'old_distributor', 'new_distributor', 'reason'
    ];

    public function request()
    {
        return $this->belongsTo(Request::class);
    }
}
