<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\LogsChanges;
use Illuminate\Database\Eloquent\SoftDeletes;
class Customer extends Model
{
    use LogsChanges;
use SoftDeletes;
    use HasFactory;
    protected $fillable = [
    'full_name', 'national_id', 'email', 'birth_date', 'address','deleted_at'
];

    /**
     * خطوط العميل (أرقام الهاتف)
     */
    public function lines(): HasMany
    {
        return $this->hasMany(Line::class);
    }
    public function invoices()
{
    return $this->hasManyThrough(Invoice::class, Line::class);
}

}
