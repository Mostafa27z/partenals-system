<?php

// app/Models/ChangeLog.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChangeLog extends Model
{
    protected $fillable = [
        'model_type',
        'model_id',
        'field_name',
        'old_value',
        'new_value',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
