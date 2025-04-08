<?php

namespace App\DTO\Backend\UserManagement;

use App\Interfaces\DTO\BaseDTOInterface;
use Illuminate\Http\Request;

class UserManagementUpdatePasswordDTO implements BaseDTOInterface
{
    /**
     * @param int $id
     * @param string $password
     * @param bool $sendEmail
     *
     * @return void
     */
    public function __construct(
        public readonly int $id,
        public readonly string $password,
        public readonly bool $sendEmail,
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
            id: self::resolveId(request: $request),
            password: $valid['password'],
            sendEmail: $valid['send_email'],
        );
    }


    /**
     * @param Request $request
     *
     * @return int
     */
    private static function resolveId(Request $request): int
    {
        return $request->user()->isGuest()
            ? $request->user()->id
            : $request->validated('id');
    }
}
