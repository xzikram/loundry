<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServicePrice extends Model
{
    protected $fillable = [
        'service_id',
        'outlet_id',
        'price_type',
        'price',
        'min_weight',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'min_weight' => 'decimal:2',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function outlet(): BelongsTo
    {
        return $this->belongsTo(Outlet::class);
    }
}
