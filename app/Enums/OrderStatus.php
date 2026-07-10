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
            self::PENDING => 'bg-amber-50 text-amber-700 border border-amber-200',
            self::PROCESSING, self::WASHING, self::DRYING, self::IRONING, self::PACKING => 'bg-blue-50 text-blue-700 border border-blue-200',
            self::READY => 'bg-indigo-50 text-indigo-700 border border-indigo-200',
            self::PICKED_UP, self::DELIVERED, self::COMPLETED => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
            self::CANCELLED => 'bg-rose-50 text-rose-700 border border-rose-200',
        };
    }
}
