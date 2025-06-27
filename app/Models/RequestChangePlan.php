<?php

// app/Models/RequestChangePlan.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestChangePlan extends Model
{
    protected $fillable = ['request_id', 'new_plan_id', 'comment'];

    public function request()
    {
        return $this->belongsTo(Request::class);
    }

    public function newPlan()
    {
        return $this->belongsTo(Plan::class, 'new_plan_id');
    }
}

