<?php

namespace App\Enums;

enum SubscriptionStatus: string
{
    case TRIAL = 'trial';
    case ACTIVE = 'active';
    case EXPIRED = 'expired';
    case CANCELLED = 'cancelled';
    case PAST_DUE = 'past_due';

    public function label(): string
    {
        return match ($this) {
            self::TRIAL => 'Trial',
            self::ACTIVE => 'Active',
            self::EXPIRED => 'Expired',
            self::CANCELLED => 'Cancelled',
            self::PAST_DUE => 'Past Due',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::TRIAL => 'info',
            self::ACTIVE => 'success',
            self::EXPIRED => 'danger',
            self::CANCELLED => 'gray',
            self::PAST_DUE => 'warning',
        };
    }
}
