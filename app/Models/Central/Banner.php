<?php

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image',
        'url',
        'position',
        'is_active',
        'sort_order',
        'starts_at',
        'expires_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
    ];
}
