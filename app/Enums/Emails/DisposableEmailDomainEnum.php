<?php

declare(strict_types=1);

namespace App\Enums\Emails;

use App\Traits\Enums\EnumValue;

enum DisposableEmailDomainEnum: string
{
    use EnumValue;

    case MAILINATOR    = 'mailinator.com';
    case TEMPMAIL      = 'tempmail.com';
    case DEMO          = 'demo.com';
    case EXAMPLE       = 'example.com';
    case EXAMPLETEMP   = 'exampletemp.com';
    case TENMINMAIL    = '10minutemail.com';
    case TEMPMAILDE    = 'temp-mail.de';
    case EMAILONDECK   = 'emailondeck.com';
    case GUERRILLAMAIL = 'guerrillamail.com';
    case YOPMAIL       = 'yopmail.com';
    case MOAKR         = 'moakt.com';
    case THROWAWAY     = 'throwawaymail.com';
    case MAILDROP      = 'maildrop.cc';
    case MAILNESIA     = 'mailnesia.com';
    case TRASHMAIL     = 'trashmail.com';
    case MAILTRASH     = 'mailtrash.net';
    case TEMPINBOX     = 'tempinbox.com';
    case DISPOSTABLE   = 'dispostable.com';
    case GETAIRMAIL    = 'getairmail.com';
    case INBOXLV       = 'inbox.lv';
    case SPAMGOURMET   = 'spamgourmet.com';
    case THIRTYTHREE   = '33mail.com';
    case BURNERMAIL    = 'burnermail.io';
    case INBOXE        = 'inbox.eu';
    case TEMPMAILORG   = 'temp-mail.org';
    case MYTRASHMAIL   = 'mytrashmail.com';
    case TEMPMAILER    = 'tempmailer.com';
    case TWENTYMINUTE  = '20minutemail.com';
    case KAPOMAIL      = 'kapomail.com';
    case SPAMMAIL      = 'spam-mail.com';
    case TRASHMAILORG  = 'trashmail.org';
    case MAILFENCE     = 'mailfence.com';
}
