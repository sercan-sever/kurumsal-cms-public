<?php

declare(strict_types=1);

namespace App\Enums\Pages\Page;

use App\Traits\Enums\EnumValue;

enum SubPageDesignEnum: string
{
    use EnumValue;

    case NONE      = "none";
    case SERVICE   = "service";
    case REFERENCE = "reference";
    case BLOG      = "blog";


    /**
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::NONE      => "- Boş -",
            self::SERVICE   => "Hizmet",
            self::REFERENCE => "Referans",
            self::BLOG      => "Blog",

            default => '',
        };
    }


    /**
     * @return array
     */
    public static function getValues(): array
    {
        return [
            self::NONE->value      => self::NONE->label(),
            self::SERVICE->value   => self::SERVICE->label(),
            self::REFERENCE->value => self::REFERENCE->label(),
            self::BLOG->value      => self::BLOG->label(),
        ];
    }
}
