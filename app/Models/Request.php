<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    protected $fillable = [
    'line_id',
    'customer_id',
    'request_type',
    'request_id',
    'status',
    'requested_by',
    'done_by',
];


    public function stopDetails()
    {
        return $this->hasOne(RequestStopLine::class);
    }
    public function requestedBy()
{
    return $this->belongsTo(User::class, 'requested_by');
}

public function doneBy()
{
    return $this->belongsTo(User::class, 'done_by');
}

public function line()
{
    return $this->belongsTo(Line::class);
}

public function resellDetails()
{
    return $this->hasOne(RequestResell::class);
}







}
