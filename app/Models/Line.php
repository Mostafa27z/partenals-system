<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\LogsChanges;
class Line extends Model
{
    use HasFactory;
    use LogsChanges;

    // تحديد الحقول التي يُسمح بتعبئتها
    protected $fillable = [
        'customer_id',
        'phone_number',
        'second_phone',
        'provider',
        'status',
        'offer_name',
        'branch_name',
        'employee_name',
        'gcode',
        'line_type',
        'plan_id',
        'package',
        'payment_date',
        'last_invoice_date',
        'notes',
        'added_by',
        'distributor',
        'attached_at','for_sale', 'sale_price',
    ];
// protected $dates = ['attached_at'];
// أو في Laravel 10+ يمكن استخدام:
// protected $casts = [
//     'attached_at' => 'date',
// ];

protected $dates = ['attached_at'];
    /**
     * العلاقة مع العميل
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * العلاقة مع الخطة
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * العلاقة مع المستخدم الذي أضاف الخط
     */
    public function addedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    /**
     * العلاقة مع الفواتير
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
