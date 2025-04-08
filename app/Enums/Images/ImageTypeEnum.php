<?php

declare(strict_types=1);

namespace App\Enums\Images;

enum ImageTypeEnum: string
{
    case MIME        = 'png,jpg,jpeg,webp';
    case MIME_ACCEPT = '.png,.jpg,.jpeg,.webp';


    /**
     * @return array<int, string>
     */
    public static function getMimeType(): array
    {
        return [
            'image/png',
            'image/jpg',
            'image/jpeg',
            'image/webp',
        ];
    }
}
