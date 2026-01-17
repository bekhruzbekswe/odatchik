<?php

namespace App\Enums;

enum CheckinStatus: string
{

    case PENDING = 'pending';
    case MISSED = 'missed';
    case DONE = 'done';

    /**
     * Get all statuses
     * @return array<string>
     */
    public static function values(): array
    {
        return array_column(self::cases() , 'value');
    }
}