<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use App\Traits\LogsChanges;
use Illuminate\Database\Eloquent\SoftDeletes;
class Plan extends Model
{
    use SoftDeletes;
    // use LogsChanges;
    protected $fillable = [
    'name', 'price', 'provider', 'provider_price', 'type', 'plan_code', 'penalty','deleted_at'
    ];

}
