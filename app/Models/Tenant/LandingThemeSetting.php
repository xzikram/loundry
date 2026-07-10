<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class LandingThemeSetting extends Model
{
    protected $table = 'landing_theme_settings';

    protected $fillable = [
        'primary_color',
        'secondary_color',
        'accent_color',
        'background_color',
        'surface_color',
        'text_color',
        'heading_color',
        'heading_font',
        'body_font',
        'button_style',
        'border_radius',
        'container_width',
        'custom_settings',
    ];

    protected $casts = [
        'custom_settings' => 'array',
    ];

    /**
     * Get or create default theme settings
     */
    public static function getSettings(): self
    {
        return self::firstOrCreate([], [
            'primary_color' => '#1E3A5F',
            'secondary_color' => '#2A5082',
            'accent_color' => '#D4A853',
            'background_color' => '#F8F9FC',
            'surface_color' => '#FFFFFF',
            'text_color' => '#4A5568',
            'heading_color' => '#1A1D23',
            'heading_font' => 'Outfit',
            'body_font' => 'Outfit',
            'button_style' => 'rounded-xl',
            'border_radius' => '12px',
            'container_width' => 'max-w-6xl',
        ]);
    }
}
