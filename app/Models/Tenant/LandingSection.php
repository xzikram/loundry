<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LandingSection extends Model
{
    protected $table = 'landing_sections';

    protected $fillable = [
        'landing_page_id',
        'section_type',
        'template_key',
        'content',
        'settings',
        'sort_order',
        'is_visible',
        'visibility_devices',
    ];

    protected $casts = [
        'content' => 'array',
        'settings' => 'array',
        'visibility_devices' => 'array',
        'is_visible' => 'boolean',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(LandingPage::class, 'landing_page_id');
    }
}
