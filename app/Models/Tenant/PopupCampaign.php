<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PopupCampaign extends Model
{
    protected $table = 'popup_campaigns';

    protected $fillable = [
        'landing_page_id',
        'name',
        'popup_type',
        'content',
        'settings',
        'trigger_type',
        'trigger_value',
        'frequency_type',
        'start_at',
        'end_at',
        'is_active',
    ];

    protected $casts = [
        'content' => 'array',
        'settings' => 'array',
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(LandingPage::class, 'landing_page_id');
    }
}
