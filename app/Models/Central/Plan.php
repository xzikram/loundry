<?php

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price_monthly',
        'price_yearly',
        'max_users',
        'max_outlets',
        'max_storage_gb',
        'trial_days',
        'is_active',
        'sort_order',
        'metadata',
    ];

    protected $casts = [
        'price_monthly' => 'decimal:2',
        'price_yearly' => 'decimal:2',
        'max_users' => 'integer',
        'max_outlets' => 'integer',
        'max_storage_gb' => 'integer',
        'trial_days' => 'integer',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'metadata' => 'array',
    ];

    public function features(): BelongsToMany
    {
        return $this->belongsToMany(Feature::class, 'plan_features')
            ->withPivot('value')
            ->withTimestamps();
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }
}
