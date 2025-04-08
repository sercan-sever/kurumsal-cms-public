<?php

declare(strict_types=1);

namespace App\Enums\Emails;

use App\Traits\Enums\EnumValue;

enum EmailEncryptionEnum: string
{
    use EnumValue;

    case SSL = "ssl";
    case TLS = "tls";
}
