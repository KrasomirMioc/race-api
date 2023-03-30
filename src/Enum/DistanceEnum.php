<?php

namespace App\Enum;


enum DistanceEnum: string
{
    case Medium = 'medium';
    case Long = 'long';

    public static function toArray(): array
    {
        return [
            self::Medium,
            self::Long
        ];
    }
}
