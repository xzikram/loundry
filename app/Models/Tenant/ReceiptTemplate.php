<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class ReceiptTemplate extends Model
{
    protected $fillable = [
        'name',
        'header',
        'footer',
        'layout',
        'is_default',
        'is_active',
    ];

    protected $casts = [
        'layout' => 'array',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];
}
