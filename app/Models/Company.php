<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'company_name',
        'company_description',
        'company_logo',
        'email_activation',
        'active_username',
        'active_password',
        'active_port',
        'suspension_penalty_days',
        'allowed_suspension_days',
        'email_problem',
        'problem_username',
        'problem_password',
        'problem_port',
        'smtp_configuration',
        'cc',
        'bcc',
        'bcc2',
        'portal_username',
        'portal_password',
    ];
}
