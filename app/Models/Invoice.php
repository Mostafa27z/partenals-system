<?php

// app/Models/Invoice.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'line_id', 'amount', 'is_paid', 'invoice_month', 'payment_date', 'paid_by', 'notes'
    ];
public function user()
{
    return $this->belongsTo(User::class, 'paid_by');
}
    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function payer() {
        return $this->belongsTo(User::class, 'paid_by');
    }
    public function line()
{
    return $this->belongsTo(Line::class);
}

// public function user()
// {
//     return $this->belongsTo(User::class, 'paid_by');
// }

    
}

