<?php

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'subject_type',
        'subject_id',
        'description',
        'causer_type',
        'causer_id',
        'properties',
        'tenant_id',
    ];

    protected $casts = [
        'properties' => 'array',
    ];
}
