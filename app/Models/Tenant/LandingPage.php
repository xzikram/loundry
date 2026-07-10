<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LandingPage extends Model
{
    protected $table = 'landing_pages';

    protected $fillable = [
        'name',
        'slug',
        'page_type',
        'status',
        'is_homepage',
        'published_at',
        'meta_title',
        'meta_description',
        'og_title',
        'og_description',
        'og_image',
    ];

    protected $casts = [
        'is_homepage' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function sections(): HasMany
    {
        return $this->hasMany(LandingSection::class, 'landing_page_id')->orderBy('sort_order');
    }
}
