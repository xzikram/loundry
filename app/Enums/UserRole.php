<?php

namespace App\Enums;

enum UserRole: string
{
    case SUPER_ADMIN = 'super_admin';
    case ADMIN = 'admin';
    case SUPPORT = 'support';
    case OWNER = 'owner';
    case MANAGER = 'manager';
    case CASHIER = 'cashier';
    case STAFF = 'staff';

    public function label(): string
    {
        return match ($this) {
            self::SUPER_ADMIN => 'Super Admin',
            self::ADMIN => 'Central Admin',
            self::SUPPORT => 'Central Support',
            self::OWNER => 'Outlet Owner',
            self::MANAGER => 'Outlet Manager',
            self::CASHIER => 'Cashier',
            self::STAFF => 'Laundry Staff',
        };
    }
}
