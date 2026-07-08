<?php

namespace App\Enums;

enum OrderStatus: string
{
    case PENDING = 'pending';
    case PROCESSING = 'processing';
    case WASHING = 'washing';
    case DRYING = 'drying';
    case IRONING = 'ironing';
    case PACKING = 'packing';
    case READY = 'ready';
    case PICKED_UP = 'picked_up';
    case DELIVERED = 'delivered';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::PROCESSING => 'Processing',
            self::WASHING => 'Washing',
            self::DRYING => 'Drying',
            self::IRONING => 'Ironing',
            self::PACKING => 'Packing',
            self::READY => 'Ready to Collect',
            self::PICKED_UP => 'Picked Up',
            self::DELIVERED => 'Delivered',
            self::COMPLETED => 'Completed',
            self::CANCELLED => 'Cancelled',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'warning',
            self::PROCESSING, self::WASHING, self::DRYING, self::IRONING, self::PACKING => 'info',
            self::READY => 'primary',
            self::PICKED_UP, self::DELIVERED => 'success',
            self::COMPLETED => 'success',
            self::CANCELLED => 'danger',
        };
    }
}
