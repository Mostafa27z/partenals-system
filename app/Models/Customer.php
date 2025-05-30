<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
    'full_name',
    'phone_number',
    'national_id',
    'status',
    'offer_name',
    'branch_name',
    'employee_name',
    'gcode',
    'provider'
];

}
