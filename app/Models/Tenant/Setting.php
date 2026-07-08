<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'group',
        'type',
    ];

    public static function getValue(string $key, mixed $default = null): mixed
    {
        $setting = self::where('key', $key)->first();
        if (! $setting) {
            return $default;
        }

        return match ($setting->type) {
            'boolean' => filter_var($setting->value, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $setting->value,
            'json' => json_decode($setting->value, true),
            default => $setting->value,
        };
    }

    public static function setValue(string $key, mixed $value, string $group = 'general', string $type = 'string'): void
    {
        self::updateOrCreate(
            ['key' => $key],
            ['value' => is_array($value) ? json_encode($value) : (string) $value, 'group' => $group, 'type' => is_array($value) ? 'json' : $type]
        );
    }
}
