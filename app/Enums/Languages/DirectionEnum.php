<?php

declare(strict_types=1);

namespace App\Enums\Languages;

use App\Traits\Enums\EnumValue;

enum DirectionEnum: string
{
    use EnumValue;

    case LTR = "ltr"; // Soldan Sağa
    case RTL = "rtl"; // Sağdan Sola
}
