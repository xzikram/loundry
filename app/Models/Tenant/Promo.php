<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image',
        'discount_value',
        'discount_type',
        'applicable_services',
        'starts_at',
        'expires_at',
        'is_active',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'applicable_services' => 'array',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];
}
