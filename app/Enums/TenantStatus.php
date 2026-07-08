<?php

namespace App\Enums;

enum TenantStatus: string
{
    case ACTIVE = 'active';
    case TRIAL = 'trial';
    case EXPIRED = 'expired';
    case SUSPENDED = 'suspended';

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'Active',
            self::TRIAL => 'Trial',
            self::EXPIRED => 'Expired',
            self::SUSPENDED => 'Suspended',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::ACTIVE => 'success',
            self::TRIAL => 'info',
            self::EXPIRED => 'danger',
            self::SUSPENDED => 'warning',
        };
    }
}
