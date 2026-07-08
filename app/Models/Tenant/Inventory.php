<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inventory extends Model
{
    use SoftDeletes;

    protected $table = 'inventory';

    protected $fillable = [
        'outlet_id',
        'name',
        'sku',
        'unit',
        'quantity',
        'min_stock',
        'price_per_unit',
        'supplier',
        'is_active',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'min_stock' => 'decimal:2',
        'price_per_unit' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function outlet(): BelongsTo
    {
        return $this->belongsTo(Outlet::class);
    }

    public function usages(): HasMany
    {
        return $this->hasMany(InventoryUsage::class);
    }
}
