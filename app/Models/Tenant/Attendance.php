<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    protected $fillable = [
        'user_id',
        'shift_id',
        'outlet_id',
        'date',
        'clock_in',
        'clock_out',
        'status',
        'notes',
        'latitude_in',
        'longitude_in',
        'latitude_out',
        'longitude_out',
    ];

    protected $casts = [
        'date' => 'date',
        'clock_in' => 'datetime',
        'clock_out' => 'datetime',
        'latitude_in' => 'decimal:7',
        'longitude_in' => 'decimal:7',
        'latitude_out' => 'decimal:7',
        'longitude_out' => 'decimal:7',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }

    public function outlet(): BelongsTo
    {
        return $this->belongsTo(Outlet::class);
    }
}
