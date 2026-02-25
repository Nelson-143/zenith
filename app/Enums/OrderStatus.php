<?php

namespace app\Enums;

enum OrderStatus: int
{
    case PENDING = 0;
    case COMPLETE = 1;
    case CANCELLED = 2; // Adjust as per your needs
    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::COMPLETE => 'Complete',
            self::CANCELLED => 'Cancelled',
        };
    }
}