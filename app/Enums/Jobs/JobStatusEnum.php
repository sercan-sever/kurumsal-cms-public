<?php

declare(strict_types=1);

namespace App\Enums\Jobs;

use App\Traits\Enums\EnumValue;

enum JobStatusEnum: string
{
    use EnumValue;

    case PROCESSING = "p"; // İşlemde
    case COMPLETED = "c"; // Tamamlandı
    case FAILED = "f"; // Hata
}
