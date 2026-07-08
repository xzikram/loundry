<?php

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image',
        'type',
        'target_url',
        'starts_at',
        'expires_at',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];
}
