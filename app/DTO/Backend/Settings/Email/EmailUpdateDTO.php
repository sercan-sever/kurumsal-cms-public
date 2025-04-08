<?php

namespace App\DTO\Backend\Settings\Email;

use App\Interfaces\DTO\BaseDTOInterface;
use Illuminate\Http\Request;

class EmailUpdateDTO implements BaseDTOInterface
{
    /**
     * @param int $settingId
     * @param string $notificatioEmail
     * @param string $senderEmail
     * @param string $subject
     *
     * @param string $host
     * @param int $port
     * @param string $encryption
     *
     * @param string $username
     * @param string $password
     *
     * @return void
     */
    public function __construct(
        public readonly int $settingId,
        public readonly string $notificatioEmail,
        public readonly string $senderEmail,
        public readonly string $subject,

        public readonly string $host,
        public readonly int $port,
        public readonly string $encryption,

        public readonly string $username,
        public readonly string $password,
    ) {
        //
    }


    /**
     * @param Request $request
     *
     * @return self
     */
    public static function fromRequest(Request $request): self
    {
        $valid = $request->validated();

        return new self(
            settingId: $valid['setting_id'],
            notificatioEmail: $valid['notification_email'],
            senderEmail: $valid['sender_email'],
            subject: $valid['subject'],

            host: $valid['host'],
            port: $valid['port'],
            encryption: $valid['encryption'],

            username: $valid['username'],
            password: $valid['password'],
        );
    }
}
