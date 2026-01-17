<?php

namespace App\Enums;

enum WalletType: string
{
    case USD = 'USD';
    case UZS = 'UZS';
    case COIN = 'COIN';

    /**
     * Get all wallet type values.
     *
     * @return array<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
