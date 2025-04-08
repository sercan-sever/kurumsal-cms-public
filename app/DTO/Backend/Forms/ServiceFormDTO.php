<?php

namespace App\DTO\Backend\Forms;

use App\Interfaces\DTO\BaseDTOInterface;
use Illuminate\Http\Request;

class ServiceFormDTO implements BaseDTOInterface
{
    /**
     * @param string $name
     * @param string $email
     * @param string $subject
     * @param string|null $phone
     * @param string $message
     * @param string $ipAddress
     *
     * @return void
     */
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $subject,
        public readonly ?string $phone,
        public readonly string $message,
        public readonly string $ipAddress,
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
            name: $valid['name'],
            email: $valid['email'],
            subject: $valid['subject'],
            phone: $valid['phone'] ?? null,
            message: htmlHideCode(code: $valid['message']),
            ipAddress: $request->ip(),
        );
    }
}
