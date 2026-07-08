<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case PENDING = 'pending';
    case PAID = 'paid';
    case FAILED = 'failed';
    case EXPIRED = 'expired';
    case REFUNDED = 'refunded';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::PAID => 'Paid',
            self::FAILED => 'Failed',
            self::EXPIRED => 'Expired',
            self::REFUNDED => 'Refunded',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'warning',
            self::PAID => 'success',
            self::FAILED => 'danger',
            self::EXPIRED => 'gray',
            self::REFUNDED => 'info',
        };
    }
}
