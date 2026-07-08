<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Enums\OrderStatus;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'invoice_number',
        'customer_id',
        'outlet_id',
        'created_by',
        'processed_by',
        'status',
        'priority',
        'subtotal',
        'discount',
        'tax',
        'total',
        'paid_amount',
        'change_amount',
        'payment_status',
        'notes',
        'special_instructions',
        'rack_location',
        'estimated_completion',
        'completed_at',
        'picked_up_at',
        'delivered_at',
        'qr_code',
        'barcode',
    ];

    protected $casts = [
        'status' => OrderStatus::class,
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'change_amount' => 'decimal:2',
        'estimated_completion' => 'datetime',
        'completed_at' => 'datetime',
        'picked_up_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function outlet(): BelongsTo
    {
        return $this->belongsTo(Outlet::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statuses(): HasMany
    {
        return $this->hasMany(OrderStatusModel::class, 'order_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(OrderPayment::class);
    }

    public function pickup(): HasOne
    {
        return $this->hasOne(Pickup::class);
    }

    public function delivery(): HasOne
    {
        return $this->hasOne(Delivery::class);
    }
}
