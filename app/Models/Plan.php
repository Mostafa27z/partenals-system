<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsChanges;
class Plan extends Model
{
    use LogsChanges;
    protected $fillable = [
    'name', 'price', 'provider', 'provider_price', 'type', 'plan_code', 'penalty'
    ];

}
