<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Customer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'gender',
        'birth_date',
        'membership_code',
        'total_orders',
        'total_spent',
        'loyalty_points',
        'metadata',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'total_orders' => 'integer',
        'total_spent' => 'decimal:2',
        'loyalty_points' => 'integer',
        'metadata' => 'array',
    ];

    public function memberships(): HasMany
    {
        return $this->hasMany(CustomerMembership::class);
    }

    public function activeMembership(): HasOne
    {
        return $this->hasOne(CustomerMembership::class)
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->latestOfMany();
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
