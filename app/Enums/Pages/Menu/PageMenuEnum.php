<?php

declare(strict_types=1);

namespace App\Enums\Pages\Menu;

use App\Traits\Enums\EnumValue;

enum PageMenuEnum: string
{
    use EnumValue;

    case NONE        = "none";
    case HEADER_MENU = "header_menu";
    case FOOTER_MENU = "footer_menu";
    case BOTH_MENU   = "both_menu";
    case SUB_MENU    = "sub_menu";


    /**
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::NONE        => "- Görünmez -",
            self::HEADER_MENU => "Üst Menü",
            self::FOOTER_MENU => "Alt Menü",
            self::BOTH_MENU   => "İkisi Birden",
            self::SUB_MENU    => "Alt Bilgi",

            default => '',
        };
    }


    /**
     * @return array
     */
    public static function getValues(): array
    {
        return [
            self::NONE->value        => self::NONE->label(),
            self::HEADER_MENU->value => self::HEADER_MENU->label(),
            self::FOOTER_MENU->value => self::FOOTER_MENU->label(),
            self::BOTH_MENU->value   => self::BOTH_MENU->label(),
            self::SUB_MENU->value    => self::SUB_MENU->label(),
        ];
    }
}
