<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pickup extends Model
{
    protected $fillable = [
        'order_id',
        'driver_id',
        'pickup_address',
        'latitude',
        'longitude',
        'scheduled_at',
        'picked_up_at',
        'status',
        'notes',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'scheduled_at' => 'datetime',
        'picked_up_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_id');
    }
}
