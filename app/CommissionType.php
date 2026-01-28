<?php

namespace App;

enum CommissionType: string
{
    case Fixed = 'fixed';
    case Percent = 'percent';

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return [
            self::Fixed->value => 'Fixed',
            self::Percent->value => 'Percent',
        ];
    }
}
