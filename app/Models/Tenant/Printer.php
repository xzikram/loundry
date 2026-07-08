<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Printer extends Model
{
    protected $fillable = [
        'outlet_id',
        'name',
        'type',
        'connection',
        'ip_address',
        'port',
        'is_default',
        'is_active',
        'config',
    ];

    protected $casts = [
        'port' => 'integer',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
        'config' => 'array',
    ];

    public function outlet(): BelongsTo
    {
        return $this->belongsTo(Outlet::class);
    }
}
