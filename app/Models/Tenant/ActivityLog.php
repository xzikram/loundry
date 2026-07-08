<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $table = 'activity_logs'; // Scoped to tenant database

    protected $fillable = [
        'subject_type',
        'subject_id',
        'description',
        'causer_id',
        'properties',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    public function causer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'causer_id');
    }
}
