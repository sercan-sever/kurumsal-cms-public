<?php

declare(strict_types=1);

namespace App\Enums\Defaults;

use App\Traits\Enums\EnumValue;

enum StatusEnum: string
{
    use EnumValue;

    case ACTIVE = "a"; // Aktif
    case PASSIVE = "p"; // Pasif
}
