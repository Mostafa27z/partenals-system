<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Customer extends Model
{
    use HasFactory;
    protected $fillable = [
        'full_name',
        'national_id',
        'birth_date',
        'email',
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
