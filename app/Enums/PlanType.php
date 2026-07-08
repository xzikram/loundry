<?php

namespace App\Enums;

enum PlanType: string
{
    case STARTER = 'starter';
    case BASIC = 'basic';
    case PRO = 'pro';
    case ENTERPRISE = 'enterprise';

    public function label(): string
    {
        return match ($this) {
            self::STARTER => 'Starter',
            self::BASIC => 'Basic',
            self::PRO => 'Pro',
            self::ENTERPRISE => 'Enterprise',
        };
    }
}
