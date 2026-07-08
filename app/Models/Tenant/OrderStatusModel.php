<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\OrderStatus;

class OrderStatusModel extends Model
{
    protected $table = 'order_statuses';

    protected $fillable = [
        'order_id',
        'user_id',
        'status',
        'notes',
        'metadata',
    ];

    protected $casts = [
        'status' => OrderStatus::class,
        'metadata' => 'array',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
