<?php

declare(strict_types=1);

namespace App\Enums\Emails;

use App\Traits\Enums\EnumValue;

enum EmailEngineEnum: string
{
    use EnumValue;

    case SMTP      = "smtp";
    case PHP_MAIL  = "php_mail";
    case SEND_MAIL = "sendmail";


    /**
     * @param string|null $engine
     *
     * @return string
     */
    public static function getValue(?string $engine): string
    {
        return match ($engine) {
            self::SMTP->value      => "smtp",
            self::PHP_MAIL->value  => "php_mail",
            self::SEND_MAIL->value => "sendmail",

            default => "",
        };
    }
}
