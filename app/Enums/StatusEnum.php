<?php

namespace App\Enums;

enum StatusEnum: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case PENDING = 'pending';
    case CONFIRMED = 'confirmed';
    case CANCELLED = 'cancelled';
    case SUCCESSFUL = 'successful';
    case FAILED = 'failed';

    public static function values(): array
    {
        return array_map(static fn ($case) => $case->value, self::cases());
    }

    public static function getUserStatuses(): array
    {
        return [
            self::ACTIVE->value,
            self::INACTIVE->value,
        ];
    }

    public static function getOrderStatuses(): array
    {
        return [
            self::PENDING->value,
            self::CONFIRMED->value,
            self::CANCELLED->value,
        ];
    }

    public static function getPaymentStatuses(): array
    {
        return [
            self::PENDING->value,
            self::SUCCESSFUL->value,
            self::FAILED->value,
        ];
    }
}
