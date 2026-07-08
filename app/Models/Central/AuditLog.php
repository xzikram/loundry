<?php

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = [
        'auditable_type',
        'auditable_id',
        'event',
        'old_values',
        'new_values',
        'user_type',
        'user_id',
        'ip_address',
        'user_agent',
        'tenant_id',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];
}
