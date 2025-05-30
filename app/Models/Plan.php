<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
    'name', 'price', 'provider', 'provider_price', 'type', 'plan_code', 'penalty'
    ];

}
