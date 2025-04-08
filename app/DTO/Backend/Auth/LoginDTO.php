<?php

namespace App\DTO\Backend\Auth;

use App\Interfaces\DTO\BaseDTOInterface;
use Illuminate\Http\Request;

class LoginDTO implements BaseDTOInterface
{
    /**
     * @param string $email
     * @param string $password
     * @param bool $remember
     *
     * @return void
     */
    public function __construct(
        public readonly string $email,
        public readonly string $password,
        public readonly bool $remember,
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
            email: $valid['email'],
            password: passwordGeneration(password: $valid['password']),
            remember: $valid['remember']
        );
    }
}
