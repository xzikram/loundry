<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryUsage extends Model
{
    protected $fillable = [
        'inventory_id',
        'order_id',
        'user_id',
        'quantity',
        'notes',
        'used_at',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'used_at' => 'datetime',
    ];

    public function inventory(): BelongsTo
    {
        return $this->belongsTo(Inventory::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
