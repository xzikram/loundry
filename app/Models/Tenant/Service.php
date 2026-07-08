<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'unit',
        'estimated_duration_hours',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'estimated_duration_hours' => 'integer',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class, 'category_id');
    }

    public function prices(): HasMany
    {
        return $this->hasMany(ServicePrice::class);
    }
}
