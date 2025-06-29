<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestResumeLine extends Model
{
    protected $fillable = ['request_id', 'reason', 'comment'];

    public function request()
    {
        return $this->belongsTo(Request::class);
    }

}
