<?php

declare(strict_types=1);

namespace App\Enums\Images;

enum ImageSizeEnum: int
{
    case SIZE_05 = 512; // 0.5mb
    case SIZE_1  = 1024; // 1mb
    case SIZE_2  = 2048; // 2mb
    case SIZE_3  = 3072; // 3mb
    case SIZE_4  = 4096; // 4mb
    case SIZE_5  = 5120; // 5mb
    case SIZE_10 = 10240; // 10mb
    case SIZE_15 = 15360; // 15mb
    case SIZE_20 = 20480; // 20mb
    case SIZE_25 = 25000; // 25mb
    case SIZE_30 = 30000; // 30mb
}
