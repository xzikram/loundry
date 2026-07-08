<?php

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\PaymentStatus;

class Payment extends Model
{
    protected $fillable = [
        'tenant_id',
        'subscription_id',
        'invoice_number',
        'amount',
        'tax',
        'total',
        'currency',
        'status',
        'payment_method',
        'gateway_transaction_id',
        'gateway_reference',
        'gateway_response',
        'paid_at',
        'expired_at',
        'refunded_at',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
        'status' => PaymentStatus::class,
        'gateway_response' => 'array',
        'paid_at' => 'datetime',
        'expired_at' => 'datetime',
        'refunded_at' => 'datetime',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }
}
