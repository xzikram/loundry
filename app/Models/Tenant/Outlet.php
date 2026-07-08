<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Outlet extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'address',
        'phone',
        'email',
        'latitude',
        'longitude',
        'operating_hours',
        'is_active',
        'is_main',
    ];

    protected $casts = [
        'operating_hours' => 'array',
        'is_active' => 'boolean',
        'is_main' => 'boolean',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function servicePrices(): HasMany
    {
        return $this->hasMany(ServicePrice::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function printers(): HasMany
    {
        return $this->hasMany(Printer::class);
    }

    public function inventory(): HasMany
    {
        return $this->hasMany(Inventory::class);
    }
}
