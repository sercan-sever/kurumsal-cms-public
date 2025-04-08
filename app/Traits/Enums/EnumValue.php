<?php

declare(strict_types=1);

namespace App\Traits\Enums;

trait EnumValue
{
    /**
     * @return array<string,string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
