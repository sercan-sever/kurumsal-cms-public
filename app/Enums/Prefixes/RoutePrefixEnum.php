<?php

declare(strict_types=1);

namespace App\Enums\Prefixes;

use App\Traits\Enums\EnumValue;

enum RoutePrefixEnum: string
{
    use EnumValue;

    case FRONTEND    = '{lang?}';
    case ADMIN_PANEL = 'lk-admin';
    case LOG_VIEWER = 'log-viewer';
    case FILE_MANAGER = 'laravel-filemanager';
}
