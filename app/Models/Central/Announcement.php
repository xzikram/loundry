<?php

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = [
        'title',
        'content',
        'type',
        'target',
        'target_plans',
        'is_active',
        'published_at',
        'expires_at',
    ];

    protected $casts = [
        'target_plans' => 'array',
        'is_active' => 'boolean',
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
    ];
}
