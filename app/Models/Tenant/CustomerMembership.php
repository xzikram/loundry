<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerMembership extends Model
{
    protected $fillable = [
        'customer_id',
        'tier',
        'discount_percentage',
        'starts_at',
        'expires_at',
        'is_active',
    ];

    protected $casts = [
        'discount_percentage' => 'decimal:2',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
