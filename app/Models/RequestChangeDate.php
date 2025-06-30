<?php

// app/Models/RequestChangeDate.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestChangeDate extends Model
{
    protected $fillable = [
        'request_id', 'current_date', 'new_date', 'reason'
    ];

    public function request()
    {
        return $this->belongsTo(Request::class);
    }
}
