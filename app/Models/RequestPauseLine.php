<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestPauseLine extends Model
{
    protected $fillable = ['request_id', 'reason', 'comment'];

    public function request()
    {
        return $this->belongsTo(Request::class);
    }
}
